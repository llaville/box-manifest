<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Composer\Manifest\DecorateTextManifestBuilder;
use Bartlett\BoxManifest\Composer\Manifest\SimpleTextManifestBuilder;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\Configuration;
use function KevinGH\Box\FileSystem\make_path_absolute;

use function array_key_exists;
use function class_exists;
use function file_exists;
use function implode;
use function is_readable;
use function is_string;

/**
 * @author Laurent Laville
 */
final class ManifestFactory
{
    public static function create(string|object $from, Configuration $config, Box $box): ?string
    {
        if (is_string($from)) {
            if (!class_exists($from)) {
                // Class provided does not exist, or is not readable by Composer Autoloader
                return null;
            }
            $builder = new $from();
        } else {
            $builder = $from;
        }

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

    public static function toHighlight(Configuration $config, Box $box): ?string
    {
        return self::create(new DecorateTextManifestBuilder(), $config, $box);
    }
}
