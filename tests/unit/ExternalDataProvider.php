<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\ManifestBuildStrategy;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class ExternalDataProvider
{
    public static function recognizedFilePatterns(): iterable
    {
        yield ['auto', null, false];
        yield ['auto', 'console-table.txt', false];
        yield ['auto', 'console-style.txt', false];
        yield ['auto', 'plain.txt', false];
        yield ['auto', 'manifest.txt', false];
        yield ['auto', 'sbom.json', false];
        yield ['auto', 'sbom.xml', false];
        yield ['auto', 'sbom.cdx.json', false];
        yield ['auto', 'sbom.cdx.xml', false];
        yield ['auto', 'custom.txt', true];
    }

    public static function recognizedOutputFormat(): iterable
    {
        yield ['console-table', null, false];
        yield ['console-table', 'whatever.you.want', false];

        yield ['console-style', null, false];
        yield ['console-style', 'whatever.you.want', false];

        yield ['plain', null, false];
        yield ['plain', 'whatever.you.want', false];

        yield ['sbom-json', null, false];
        yield ['sbom-json', 'whatever.you.want', false];

        yield ['sbom-xml', null, false];
        yield ['sbom-xml', 'whatever.you.want', false];

        yield ['\My\Space\ClassNotFound', null, true];
        yield ['\My\Space\ClassNotFound', 'whatever.you.want', true];
    }

    public static function recognizedMimeType(): iterable
    {
        yield ['console-table.txt', ManifestBuildStrategy::MIME_TYPE_OCTET_STREAM];
        yield ['console-style.txt', ManifestBuildStrategy::MIME_TYPE_OCTET_STREAM];
        yield ['plain.txt', ManifestBuildStrategy::MIME_TYPE_TEXT_PLAIN];
        yield ['manifest.txt', ManifestBuildStrategy::MIME_TYPE_TEXT_PLAIN];
        yield ['sbom.json', ManifestBuildStrategy::MIME_TYPE_SBOM_JSON];
        yield ['sbom.xml', ManifestBuildStrategy::MIME_TYPE_SBOM_XML];
        yield ['sbom.cdx.json', ManifestBuildStrategy::MIME_TYPE_SBOM_JSON];
        yield ['sbom.cdx.xml', ManifestBuildStrategy::MIME_TYPE_SBOM_XML];
        yield ['whatever.you.want', ManifestBuildStrategy::MIME_TYPE_OCTET_STREAM];
    }
}
