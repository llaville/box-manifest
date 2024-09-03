<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Fidry\Console\IO;

use Psr\Log\LoggerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

use function file_exists;
use function file_get_contents;
use function fopen;
use function realpath;
use function sprintf;
use function unserialize;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
abstract readonly class AbstractStage
{
    public const BOX_MANIFESTS_DIR = '.box.manifests/';
    public const META_DATA_FILE = '.box.manifests.bin';

    private ?HelperInterface $debugFormatterHelper;

    /**
     * @param array{pid: string} $context
     */
    public function __construct(
        protected IO $io,
        protected Command $command,
        protected LoggerInterface $logger,
        protected array $context
    ) {
        $this->debugFormatterHelper = $this->command->getHelperSet()?->has('debug_formatter')
            ? $this->command->getHelper('debug_formatter')
            : null
        ;
    }

    public static function create(IO $io, Command $command, LoggerInterface $logger, array $context): static
    {
        return new static($io, $command, $logger, $context);
    }

    /**
     * @param string|string[] $contents
     */
    protected function writeToStream(string $filename, string|iterable $contents, string $reason = 'Unable to write', array $context = []): int
    {
        $resource = fopen($filename, 'w');
        if (!$resource) {
            $message = sprintf('%s to file "%s"', $reason, realpath($filename));
            $this->logger->warning($message, $context);
            return 0;
        }

        $stream = new StreamOutput($resource);
        $stream->write($contents, true, OutputInterface::OUTPUT_RAW);
        fclose($stream->getStream());
        $this->logger->debug(
            sprintf('%s written to "%s"', $resource, str_starts_with($filename, 'php://') ? $filename : realpath($filename)),
            $context
        );
        return 1;
    }

    /**
     * @return array<string, string>
     */
    protected function getMetaData(): array
    {
        if (file_exists(self::META_DATA_FILE)) {
            // @phpstan-ignore argument.type
            $metadata = unserialize(file_get_contents(self::META_DATA_FILE));
        } else {
            $metadata = [];
        }

        return $metadata;
    }
}
