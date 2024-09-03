<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Console\Application;
use Bartlett\BoxManifest\Console\Command\Make;
use Bartlett\BoxManifest\Pipeline\StageInterface;

use PHPUnit\Framework\Attributes\DataProviderExternal;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use Exception;

/**
 * End-to-end tests for pipeline "make build" command.
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class BuildStageTest extends TestCase
{
    private Application $application;

    private BufferedOutput $output;

    protected function setUp(): void
    {
        $this->application = new Application();
        $this->application->add(new Make());
        $this->application->setAutoExit(false);
    }

    /**
     * @throws Exception
     */
    #[DataProviderExternal(ExternalDataProvider::class, 'wrongResources')]
    public function testBuildResourcesWithoutSuccess(
        string $outputFormat,
        array $resources,
        ?string $expectedMessage = null
    ): void {
        $exitCode = $this->runApplication($outputFormat, $resources);
        $this->assertSame(Command::FAILURE, $exitCode);
        $this->assertStringContainsString($expectedMessage, $this->output->fetch());
    }

    /**
     * @throws Exception
     */
    #[DataProviderExternal(ExternalDataProvider::class, 'goodResources')]
    public function testBuildResourcesWithSuccess(
        string $outputFormat,
        array $resources,
        ?string $expectedMessage = null
    ): void {
        $exitCode = $this->runApplication($outputFormat, $resources);
        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($expectedMessage, $this->output->fetch());
    }

    /**
     * @param string[] $resources
     * @throws Exception
     */
    protected function runApplication(string $outputFormat, array $resources): int
    {
        $parameters = [
            'make',
            'stages' => [StageInterface::BUILD_STAGE],
            '--immutable' => true,
            '--verbose=3',
            '--output-format' => $outputFormat,
            '--resource' => $resources,
            '--no-config' => true,
        ];

        $this->output = new BufferedOutput();
        $input = new ArrayInput($parameters);

        return $this->application->run($input, $this->output);
    }
}
