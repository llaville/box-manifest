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
enum Manifest: string
{
    case txt = 'manifest.txt';
    case xml = 'manifest.xml';
    case sbomXml = 'sbom.xml';
    case sbomJson = 'sbom.json';

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
