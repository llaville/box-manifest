<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Bartlett\BoxManifest\Console\Logger;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use RuntimeException;
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
        $context = ['status' => Logger::STATUS_RUNNING, 'id' => $payload['pid']];

        $configurationFile = $payload['outputConf'] ?? $payload['config'];

        $process = $this->createBoxProcess($configurationFile);
        $process->run();

        if (!$process->isSuccessful()) {
            $context['error'] = true;
            $this->logger->error($process->getCommandLine(), $context);
            $this->logger->error($process->getErrorOutput(), $context);
            throw new RuntimeException('Unable to run BOX compile process');
        }

        $this->logger->notice($process->getOutput(), $context);

        return $payload;
    }

    private function createBoxProcess(?string $configurationFile = null): Process
    {
        $command = [
            PHP_SAPI == 'cli' ? PHP_BINARY : PHP_BINDIR . '/php',
            realpath(dirname(__DIR__, 2) . '/vendor/bin/box'),
            'compile',
        ];

        if ($configurationFile) {
            $command[] = '--config';
            $command[] = $configurationFile;
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
