<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\BoxManifest\Helper\ManifestFile;
use Bartlett\BoxManifest\Helper\ManifestHelper;

use Composer\InstalledVersions;

/**
 * Example of a simple callback that may be used to generate contents for the PHAR `metadata` field.
 *
 * E.g:
 * ```json
 *     "metadata": "MyCallbacks::metadata"
 * ```
 * @link https://github.com/box-project/box/blob/main/doc/configuration.md#metadata-metadata
 *
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class MyCallbacks
{
    public static function metadata(): ?string
    {
        $rootPackage = InstalledVersions::getRootPackage();
        return sprintf('%s@%s', $rootPackage['pretty_version'], substr($rootPackage['reference'], 0, 7));
    }

    public static function manifest(array $resources = null): ?string
    {
        if (empty($resources)) {
            $resources = ManifestFile::values();
        }
        return (new ManifestHelper())->get($resources) ?? self::metadata();
    }
}
