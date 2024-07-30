<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Helper;

use Fidry\Console\IO;

use KevinGH\Box\Configuration\Configuration;
use KevinGH\Box\Console\Command\ConfigOption;
use KevinGH\Box\Console\Php\PhpSettingsHandler;
use function KevinGH\Box\get_box_version;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;

use stdClass;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class BoxHelper extends Helper
{
    public const NAME = 'box';

    public const NO_CONFIG_OPTION = 'no-config';
    public const CONFIG_PARAM = 'config';

    public function getBoxVersion(): string
    {
        return get_box_version();
    }

    public function checkPhpSettings(IO $io): void
    {
        (new PhpSettingsHandler(new ConsoleLogger($io->getOutput())))->check();
    }

    /**
     * @return array<InputOption>
     */
    public function getBoxConfigOptions(): array
    {
        return [
            new InputOption(
                self::NO_CONFIG_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Ignore the config file even when one is specified with the --config option',
            ),
            new InputOption(
                self::CONFIG_PARAM,
                'c',
                InputOption::VALUE_REQUIRED,
                'The alternative configuration file path.',
            ),
        ];
    }

    public function getBoxConfiguration(IO $io, bool $allowNoFile = false, bool $noConfig = false): Configuration
    {
        if ($noConfig) {
            return Configuration::create(null, new stdClass());
        }
        return ConfigOption::getConfig($io, $allowNoFile);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
