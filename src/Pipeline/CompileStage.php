<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use KevinGH\Box\Configuration\Configuration;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function dirname;
use function realpath;
use const PHP_BINARY;
use const PHP_BINDIR;
use const PHP_SAPI;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final readonly class CompileStage extends AbstractStage implements StageInterface
{
    public function __invoke(array $payload): array
    {
        $config = $payload['configuration'];

        $process = $this->createBoxProcess($config);
        $process->run();

        if (!$process->isSuccessful()) {
            $messages = ['Unable to run BOX compile process.'];
            if ($this->io->isVeryVerbose()) {
                $messages[] = $process->getCommandLine();
            }
            if ($this->io->isDebug()) {
                $messages[] = $process->getErrorOutput();
            }
            $this->io->error($messages);

            return $payload;
        }

        if ($this->io->isVeryVerbose()) {
            $this->io->write($process->getOutput());
        }

        return $payload;
    }

    private function createBoxProcess(Configuration $config): Process
    {
        $command = [
            PHP_SAPI == 'cli' ? PHP_BINARY : PHP_BINDIR . '/php',
            realpath(dirname(__DIR__, 2) . '/vendor/bin/box'),
            'compile',
        ];

        if ($conf = $config->getConfigurationFile()) {
            $command[] = '--config';
            $command[] = $conf;
        }

        $verbosity = match ($this->io->getVerbosity()) {
            OutputInterface::VERBOSITY_VERBOSE => '-v',
            OutputInterface::VERBOSITY_VERY_VERBOSE => '-vv',
            OutputInterface::VERBOSITY_DEBUG => '-vvv',
            default => '',
        };

        if (!empty($verbosity)) {
            $command[] = $verbosity;
        }

        $command[] = $this->io->isDecorated() ? '--ansi' : '--no-ansi';

        return new Process($command);
    }
}
