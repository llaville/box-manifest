<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Fidry\Console\IO;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

use function fopen;
use function is_string;
use function realpath;
use function sprintf;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
abstract readonly class AbstractStage
{
    private ?HelperInterface $debugFormatterHelper;

    /**
     * @param array{pid: string} $context
     */
    public function __construct(protected IO $io, protected Command $command, private array $context)
    {
        $this->debugFormatterHelper = $this->command->getHelperSet()?->has('debug_formatter')
            ? $this->command->getHelper('debug_formatter')
            : null
        ;
    }

    /**
     * @param string|string[] $contents
     */
    protected function writeToStream(string $filename, string|iterable $contents, string $reason = 'Unable to write'): int
    {
        $resource = fopen($filename, 'w');
        if (!$resource) {
            $message = sprintf('- %s to file "<comment>%s</comment>"', $reason, realpath($filename));
            $this->io->warning($message);
            return 0;
        }

        $stream = new StreamOutput($resource);
        $stream->write($contents, false, OutputInterface::OUTPUT_RAW);
        fclose($stream->getStream());
        return 1;
    }

    /**
     * @param string|string[] $messages
     */
    protected function debugPrintStage(string|iterable $messages, bool $newline = false): void
    {
        if (!$this->io->isDebug()) {
            // do nothing if debug mode is disabled
            return;
        }

        if (is_string($messages)) {
            $messages = [$messages];
        }

        // 1. try first with Symfony Console Debug Formatter Helper (if available)
        //    @see https://symfony.com/doc/current/components/console/helpers/debug_formatter.html
        if ($this->debugFormatterHelper instanceof DebugFormatterHelper) {
            foreach ($messages as $message) {
                $this->debugFormatterHelper->progress(
                    $this->context['pid'],
                    $message
                );
            }
            return;
        }

        // 2. fallback is to print on standard error
        $stream = new StreamOutput(fopen('php://stderr', 'w'));  // @phpstan-ignore argument.type
        $stream->write($messages, $newline);
        fclose($stream->getStream());
    }
}
