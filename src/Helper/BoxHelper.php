<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Helper;

use Bartlett\BoxManifest\Composer\RestartHandler;

use Fidry\Console\IO;

use function KevinGH\Box\get_box_version;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class BoxHelper extends Helper
{
    public const NAME = 'box';

    public function getBoxVersion(): string
    {
        return get_box_version();
    }

    public function checkPhpSettings(IO $io): void
    {
        $out = $io->getOutput();
        $out->setVerbosity(OutputInterface::VERBOSITY_DEBUG);

        $restart = (new RestartHandler('box'));
        $restart->setLogger(new ConsoleLogger($out));
        $restart->check();
    }

    /**
     * @return array<InputOption>
     */
    public function getBoxConfigOptions(): array
    {
        return [
            new InputOption(
                BoxConfigurationHelper::NO_CONFIG_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Ignore the config file even when one is specified with the --config option',
            ),
            new InputOption(
                BoxConfigurationHelper::CONFIG_PARAM,
                'c',
                InputOption::VALUE_REQUIRED,
                'The alternative configuration file path.',
            ),
        ];
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
