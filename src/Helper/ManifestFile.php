<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Helper;

use function array_column;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
enum ManifestFile: string
{
    case custom = 'custom.bin';
    case txt = 'manifest.txt';
    case plain = 'plain.txt';
    case xml = 'manifest.xml';
    case sbomXml = 'sbom.xml';
    case sbomJson = 'sbom.json';
    case consoleTable = 'console-table.txt';
    case consoleStyle = 'console-style.txt';

    /**
     * Domain of valid values.
     *
     * @link https://www.php.net/manual/en/language.enumerations.static-methods.php#126866
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
