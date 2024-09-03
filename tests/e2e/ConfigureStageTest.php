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
 * End-to-end tests for pipeline "make configure" command.
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class ConfigureStageTest extends TestCase
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
    #[DataProviderExternal(ExternalDataProvider::class, 'goodConfig')]
    public function testBuildConfigurationWithSuccess(
        string $outputStub,
        string $outputConf,
        ?string $configurationFile,
        ?string $workingDir,
        ?string $expectedMessage = null
    ): void {

        $exitCode = $this->runApplication($outputStub, $outputConf, $configurationFile, $workingDir);
        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($expectedMessage, $this->output->fetch());
    }

    /**
     * @throws Exception
     */
    protected function runApplication(string $outputStub, string $outputConf, ?string $configurationFile, ?string $workingDir): int
    {
        $parameters = [
            'make',
            'stages' => [StageInterface::CONFIGURE_STAGE],
            '--immutable' => true,
            '--verbose=3',
            '--output-stub' => $outputStub,
            '--output-conf' => $outputConf,
        ];

        if (empty($configurationFile)) {
            $parameters['--no-config'] = true;
        } else {
            $parameters['--config'] = $configurationFile;
        }

        if (!empty($workingDir)) {
            $parameters['--working-dir'] = $workingDir;
        }

        $this->output = new BufferedOutput();
        $input = new ArrayInput($parameters);

        return $this->application->run($input, $this->output);
    }
}
