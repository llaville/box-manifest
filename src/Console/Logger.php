<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console;

use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

use function trim;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
class Logger extends ConsoleLogger
{
    public const STATUS_STARTED = 'started';
    public const STATUS_RUNNING = 'running';
    public const STATUS_STOPPED = 'stopped';

    /**
     * @param array<string, int> $verbosityLevelMap
     * @param array<string, string> $formatLevelMap
     */
    public function __construct(
        protected DebugFormatterHelper $helper,
        OutputInterface $output,
        array $verbosityLevelMap = [],
        array $formatLevelMap = []
    ) {
        parent::__construct($output, $verbosityLevelMap, $formatLevelMap);
    }

    /**
     * @param array{
     *    status?: string,
     *    id?: int,
     *    error?: boolean,
     *    prefix?: string,
     *    errorPrefix?: string
     * } $context
     */
    public function log($level, $message, array $context = []): void
    {
        if (isset($context['status']) && isset($context['id'])) {
            $id = $context['id'];
            $error = $context['error'] ?? false;
            $message = match ($context['status']) {
                self::STATUS_STARTED => $this->helper->start($id, $message, $context['prefix'] ?? 'RUN'),
                self::STATUS_RUNNING => $this->helper->progress($id, $message, $error, $context['prefix'] ?? 'OUT', $context['errorPrefix'] ?? 'ERR'),
                self::STATUS_STOPPED => trim($this->helper->stop($id, $message, !$error, $context['prefix'] ?? 'RES')),
                default => $message, // uses raw message if status is unknown
            };
        }
        parent::log($level, $message, $context);
    }
}
