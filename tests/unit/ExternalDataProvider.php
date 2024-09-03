<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\ManifestBuildStrategy;

use function realpath;
use function sprintf;

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

    public static function recognizedBuildStageOptions(): iterable
    {
        yield ['auto', ['plain.txt'], null, null, null, 'bootstrap.php'];
        yield ['auto', ['plain.txt', 'console-table.txt', 'sbom.json']];
        yield ['auto', [], '1.5'];
        yield ['auto', [], null, 'sbom.cdx.xml'];
        yield ['auto', [], null, null, 'my-box.json.dist'];

        yield ['plain', []];
    }

    public static function recognizedStubStageOptions(): iterable
    {
        $validWorkingDir = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev';
        $invalidWorkingDir = __DIR__ . '/../fixtures/my-project';

        yield [false, 'my-stub.php', null, null, $validWorkingDir];
        yield [true, null, 'empty_stub.template', '.my.manifests/', $invalidWorkingDir];
    }

    public static function wrongResources(): iterable
    {
        $resourceFile = 'undetectable_resource.format';
        yield ['auto', [$resourceFile], sprintf('Cannot auto-detect format for "%s" resource file', $resourceFile)];

        $outputFormat = '\My\Space\ClassNotFound';
        yield [$outputFormat, ['custom.bin'], sprintf('Format "%s" is not supported', $outputFormat)];
    }

    public static function goodResources(): iterable
    {
        yield ['auto', ['plain.txt'], '1 manifest was built'];
        yield ['auto', ['plain.txt', 'sbom.json'], '2 manifests were built'];

        yield ['console-table', ['console.txt', 'manifest.console_table_format'], '2 manifests were built'];
    }

    public static function goodStub(): iterable
    {
        $workingDir = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev';

        $outputStub = 'stub.php';
        $expectedMessage = sprintf('written to "%s/%s"', realpath($workingDir), $outputStub);

        yield [$outputStub, 'box.json', $workingDir, $expectedMessage];
    }

    public static function goodConfig(): iterable
    {
        $workingDir = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev';

        $outputStub = 'stub.php';
        $outputConf = 'box.json.dist';
        $expectedMessage = sprintf('written to "%s/%s"', realpath($workingDir), $outputConf);

        yield [$outputStub, $outputConf, 'box.json', $workingDir, $expectedMessage];
    }
}
