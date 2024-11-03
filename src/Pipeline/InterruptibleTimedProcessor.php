<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Bartlett\BoxManifest\Console\Logger;

use League\Pipeline\ProcessorInterface;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use Symfony\Component\Stopwatch\Stopwatch;

use RuntimeException;
use Throwable;
use function is_array;
use function sprintf;

/**
 * Starts a timer before entering the stage, and logs the expired time afterward.
 * Implements also a circuit breaker feature.
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final readonly class InterruptibleTimedProcessor implements ProcessorInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @throws Throwable
     */
    public function process($payload, callable ...$stages)
    {
        if (!is_array($payload)) {
            return $payload;
        }

        $stopwatch = new Stopwatch();

        foreach ($stages as $stage) {
            $name = $stage::class;  // @phpstan-ignore classConstant.nonObject
            $stopwatch->start($name);
            $pid = $payload['pid'] = uniqid();

            $this->logger->debug(
                sprintf('Starting stage "%s"', $name),
                ['status' => Logger::STATUS_STARTED, 'id' => $pid]
            );

            try {
                $payload = $stage($payload);
                $event = $stopwatch->stop($name);
                $level = LogLevel::NOTICE;
                $message = sprintf('Completed stage "%s" in %d ms', $name, $event->getDuration());
                $isSuccessful = true;
            } catch (RuntimeException $exception) {
                // Runtime errors that do not require immediate action but should typically be logged and monitored
                $level = LogLevel::ERROR;
                $isSuccessful = false;
            } catch (Throwable $exception) {
                // Critical conditions
                $level = LogLevel::CRITICAL;
                $isSuccessful = false;
            } finally {
                if (!$isSuccessful) {
                    // @phpstan-ignore-next-line variable.undefined
                    $message = sprintf('The stage "%s" has failed : %s', $name, $exception->getMessage());
                }
                // @phpstan-ignore-next-line variable.undefined
                $this->logger->log($level, $message, ['status' => Logger::STATUS_STOPPED, 'id' => $pid, 'error' => !$isSuccessful]);

                if (!$isSuccessful) {
                    // circuit breaker or critical conditions lead to abort the workflow
                    throw $exception;  // @phpstan-ignore variable.undefined
                }
            }
        }

        return $payload;
    }
}
