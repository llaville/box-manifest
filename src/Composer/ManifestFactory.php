<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Composer\Manifest\SimpleTextManifestBuilder;
use Bartlett\BoxManifest\Composer\Manifest\SimpleXmlManifestBuilder;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\Configuration;
use function KevinGH\Box\FileSystem\make_path_absolute;

use function array_key_exists;
use function class_exists;
use function file_exists;
use function implode;
use function is_readable;

/**
 * @author Laurent Laville
 */
final class ManifestFactory
{
    public static function create(string $fromClass, Configuration $config, Box $box): ?string
    {
        if (!class_exists($fromClass)) {
            // Class provided does not exist, or is not readable by Composer Autoloader
            return null;
        }
        $builder = new $fromClass();
        if (!$builder instanceof ManifestBuilderInterface) {
            // Your manifest class builder is not compatible.
            return null;
        }

        // The composer.lock and installed.php are optional (e.g. if there is no dependencies installed)
        // but when one is present, the other must be as well
        $decodedJsonContents = $config->getDecodedComposerJsonContents();

        $composerLock = $config->getComposerLock();
        if (null === $composerLock) {
            // No dependencies installed
            $installedPhp = [];
        } else {
            $normalizePath = function ($file, $basePath) {
                return make_path_absolute(trim($file), $basePath);
            };

            $basePath = $config->getBasePath();

            if (null !== $decodedJsonContents && array_key_exists('vendor-dir', $decodedJsonContents)) {
                $vendorDir = $normalizePath($decodedJsonContents['vendor-dir'], $basePath);
            } else {
                $vendorDir = $normalizePath('vendor', $basePath);
            }

            $file = implode(DIRECTORY_SEPARATOR, [$vendorDir, 'composer', 'installed.php']);
            if (!file_exists($file) || !is_readable($file)) {
                return null;
            }
            $installedPhp = include $file;
        }

        return $builder(
            [
                'composer.json' => $decodedJsonContents,
                'composer.lock' => $config->getDecodedComposerLockContents(),
                'installed.php' => (array) $installedPhp,
            ]
        );
    }

    public static function toText(Configuration $config, Box $box): ?string
    {
        return self::create(SimpleTextManifestBuilder::class, $config, $box);
    }

    public static function toXml(Configuration $config, Box $box): ?string
    {
        return self::create(SimpleXmlManifestBuilder::class, $config, $box);
    }
}
