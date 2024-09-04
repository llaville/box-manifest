<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\ManifestOptions;
use Bartlett\BoxManifest\Console\Command\Make;
use Bartlett\BoxManifest\Helper\ManifestFormat;
use Bartlett\BoxManifest\Pipeline\StageInterface;

use Fidry\Console\IO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

use RuntimeException;

/**
 * Unit tests for ManifestOptions component of the Box Manifest
 *
 * @author Laurent Laville
 */
#[CoversClass(ManifestOptions::class)]
final class ManifestOptionsTest extends TestCase
{
    #[DataProviderExternal(ExternalDataProvider::class, 'recognizedBuildStageOptions')]
    public function testBuildStageOptions(
        string $outputFormat,
        array $resources,
        ?string $sbomSpec = null,
        ?string $outputFile = null,
        ?string $outputConfFile = null,
        ?string $bootstrap = null,
    ): void {
        $parameters = [
            'stages' => [StageInterface::BUILD_STAGE],
            '--immutable' => true,
        ];

        if (!empty($outputFormat)) {
            $parameters['--output-format'] = $outputFormat;
        }
        if (!empty($resources)) {
            $parameters['--resource'] = $resources;
        }
        if (!empty($sbomSpec)) {
            $parameters['--sbom-spec'] = $sbomSpec;
        }
        if (!empty($outputFile)) {
            $parameters['--output-file'] = $outputFile;
        }
        if (!empty($outputConfFile)) {
            $parameters['--output-conf'] = $outputConfFile;
        }
        if (!empty($bootstrap)) {
            $parameters['--bootstrap'] = $bootstrap;
        }

        $io = new IO(new ArrayInput($parameters, (new Make())->getDefinition()), new NullOutput());
        $options = new ManifestOptions($io);

        // --output-format
        $this->assertSame($outputFormat, $options->getFormat(true));

        if (ManifestFormat::plain->value === $outputFormat) {
            $this->assertSame(ManifestFormat::plain, $options->getFormat());
        }

        // --resource
        $this->assertSame($resources, $options->getResources());

        // --sbom-spec
        if (!empty($sbomSpec)) {
            $this->assertSame($sbomSpec, $options->getSbomSpec());
        }

        // --output-file
        if (!empty($outputFile)) {
            $this->assertSame($outputFile, $options->getOutputFile());
        }


        // --output-conf
        if (!empty($outputConfFile)) {
            $this->assertSame($outputConfFile, $options->getOutputConfFile());
        }

        // --bootstrap
        if (!empty($bootstrap)) {
            $this->assertSame($bootstrap, $options->getBootstrap());
        }

        // --immutable
        $this->assertTrue($options->isImmutable());
    }

    #[DataProviderExternal(ExternalDataProvider::class, 'recognizedStubStageOptions')]
    public function testStubStageOptions(
        bool $expectedException = false,
        ?string $outputStubFile = null,
        ?string $templateFile = null,
        ?string $resourceDir = null,
        ?string $workingDir = null,
    ): void {
        if ($expectedException) {
            $this->expectException(RuntimeException::class);
        }

        $parameters = [
            'stages' => [StageInterface::STUB_STAGE],
            '--immutable' => true,
        ];

        if (!empty($outputStubFile)) {
            $parameters['--output-stub'] = $outputStubFile;
        }

        if (!empty($templateFile)) {
            $parameters['--template'] = $templateFile;
        }

        if (!empty($resourceDir)) {
            $parameters['--resource-dir'] = $resourceDir;
        }

        if (!empty($workingDir)) {
            $parameters['--working-dir'] = $workingDir;
        }

        $io = new IO(new ArrayInput($parameters, (new Make())->getDefinition()), new NullOutput());
        $options = new ManifestOptions($io);

        // --output-stub
        if (!empty($outputStubFile)) {
            $this->assertSame($outputStubFile, $options->getOutputStubFile());
        }

        // --template
        if (!empty($templateFile)) {
            $this->assertSame($templateFile, $options->getTemplateFile());
        }

        // --resource-dir
        if (!empty($resourceDir)) {
            $this->assertSame($resourceDir, $options->getResourceDir());
        }

        // --working-dir
        if (!empty($workingDir)) {
            $this->assertSame($workingDir, $options->getWorkingDir());
        }
    }
}