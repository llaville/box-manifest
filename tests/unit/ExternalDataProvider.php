<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class ExternalDataProvider
{
    public static function recognizedFilePatterns(): iterable
    {
        yield ['auto', null, false];
        yield ['auto', 'console.txt', false];
        yield ['auto', 'plain.txt', false];
        yield ['auto', 'manifest.txt', false];
        yield ['auto', 'ansi.txt', false];
        yield ['auto', 'sbom.json', false];
        yield ['auto', 'sbom.xml', false];
        yield ['auto', 'sbom.cdx.json', false];
        yield ['auto', 'sbom.cdx.xml', false];
        yield ['auto', 'custom.txt', true];
    }

    public static function recognizedOutputFormat(): iterable
    {
        yield ['console', null, false];
        yield ['console', 'whatever.you.want', false];

        yield ['plain', null, false];
        yield ['plain', 'whatever.you.want', false];

        yield ['ansi', null, false];
        yield ['ansi', 'whatever.you.want', false];

        yield ['sbom-json', null, false];
        yield ['sbom-json', 'whatever.you.want', false];

        yield ['sbom-xml', null, false];
        yield ['sbom-xml', 'whatever.you.want', false];

        yield ['\My\Space\ClassNotFound', null, true];
        yield ['\My\Space\ClassNotFound', 'whatever.you.want', true];
    }
}
