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
 * End-to-end tests for pipeline "make stub" command.
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class StubStageTest extends TestCase
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
    #[DataProviderExternal(ExternalDataProvider::class, 'goodStub')]
    public function testBuildStubWithSuccess(
        string $outputStub,
        ?string $configurationFile,
        ?string $workingDir,
        ?string $expectedMessage = null
    ): void {

        $exitCode = $this->runApplication($outputStub, $configurationFile, $workingDir);
        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($expectedMessage, $this->output->fetch());
    }

    /**
     * @throws Exception
     */
    protected function runApplication(string $outputStub, ?string $configurationFile, ?string $workingDir): int
    {
        $parameters = [
            'make',
            'stages' => [StageInterface::STUB_STAGE],
            '--immutable' => true,
            '--verbose=3',
            '--output-stub' => $outputStub,
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
