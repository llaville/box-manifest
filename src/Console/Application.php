<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console;

use Bartlett\BoxManifest\Console\Command\Manifest;
use Fidry\Console\Application\Application as FidryApplication;

use function KevinGH\Box\get_box_version;
use function sprintf;
use function trim;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class Application implements FidryApplication
{
    public function __construct(private string $name = 'Box-Manifest', private string $version = '3.x-dev')
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getLongVersion(): string
    {
        return trim(
            sprintf(
                '<info>%s</info> version <comment>%s</comment> for Box <comment>%s</comment>',
                $this->getName(),
                $this->getVersion(),
                get_box_version()
            ),
        );
    }

    public function getHelp(): string
    {
        return $this->getLongVersion();
    }

    public function getCommands(): array
    {
        return [
            new Manifest($this->getLongVersion()),
        ];
    }

    public function getDefaultCommand(): string
    {
        return 'list';
    }

    public function isAutoExitEnabled(): bool
    {
        return true;
    }

    public function areExceptionsCaught(): bool
    {
        return true;
    }
}
