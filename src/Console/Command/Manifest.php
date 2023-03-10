<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Bartlett\BoxManifest\Composer\ManifestFactory;

use Fidry\Console\Command\CommandAware;
use Fidry\Console\Command\CommandAwareness;
use Fidry\Console\Command\Configuration as CommandConfiguration;
use Fidry\Console\Command\LazyCommand;
use Fidry\Console\ExitCode;
use Fidry\Console\Input\IO;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\Configuration;
use KevinGH\Box\Console\Command\ConfigOption;
use function KevinGH\Box\check_php_settings;
use function KevinGH\Box\get_box_version;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

use stdClass;
use function fclose;
use function file_exists;
use function fopen;
use function sprintf;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class Manifest implements CommandAware, LazyCommand
{
    use CommandAwareness;

    public const NAME = 'contrib:add-manifest';

    private const HELP = <<<'HELP'
        The <info>%command.name%</info> command will generate a manifest of your application.
        HELP;

    private const NO_CONFIG_OPTION = 'no-config';

    private const BOOTSTRAP_OPTION = 'bootstrap';
    private const FORMAT_OPTION = 'format';
    private const OUTPUT_OPTION = 'output-file';

    public function __construct(private string $header)
    {
    }

    public function getConfiguration(): CommandConfiguration
    {
        $arguments = [];
        $options = [
            new InputOption(
                self::BOOTSTRAP_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'A PHP script that is included before execution',
            ),
            new InputOption(
                self::FORMAT_OPTION,
                'f',
                InputOption::VALUE_REQUIRED,
                'Format of the output: auto, plain, ansi, sbom',
                'auto'
            ),
            new InputOption(
                self::OUTPUT_OPTION,
                'o',
                InputOption::VALUE_REQUIRED,
                'Write results to file (<comment>default to standard output</comment>)'
            ),
            new InputOption(
                self::NO_CONFIG_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Ignore the config file even when one is specified with the --config option',
            ),
            ConfigOption::getOptionInput(),
        ];

        return new CommandConfiguration(
        // The name of the command (the part after "bin/console")
            static::getName(),
            // The short description shown while running "php bin/console list"
            static::getDescription(),
            // The full command description shown when running the command with
            // the "--help" option
            self::HELP,
            $arguments,
            $options,
        );
    }

    public function execute(IO $io): int
    {
        check_php_settings($io);

        $io->writeln($this->header);

        if (!empty($bootstrap = $io->getOption(self::BOOTSTRAP_OPTION)->asNullableString()) && file_exists($bootstrap)) {
            $io->writeln(
                sprintf('<info>[debug] Bootstrapped file "%s"</info>', $bootstrap),
                OutputInterface::VERBOSITY_DEBUG,
            );
            include $bootstrap;
        }

        $config = $io->getOption(self::NO_CONFIG_OPTION)->asBoolean()
            ? Configuration::create(null, new stdClass())
            : ConfigOption::getConfig($io, true);

        $box = Box::create($config->getTmpOutputPath());

        $output = $io->getOption(self::OUTPUT_OPTION)->asNullableString();
        $format = $io->getOption(self::FORMAT_OPTION)->asString();

        $factory = new ManifestFactory($config, $box, get_box_version());
        $manifest = $factory->build($format, $output);

        if ($io->isVerbose() || empty($output)) {
            $io->writeln($manifest);

            $io->comment('Writing results to standard output');
        } else {
            $stream = new StreamOutput(fopen($output, 'w'));
            if ('ansi' === $format) {
                $stream->setDecorated(true);
            }
            $stream->write($manifest);
            fclose($stream->getStream());

            $io->comment(sprintf('Writing manifest to file "<comment>%s</comment>"', $output));
        }

        return ExitCode::SUCCESS;
    }

    public static function getName(): string
    {
        return self::NAME;
    }

    public static function getDescription(): string
    {
        return 'Creates a manifest of your software components and dependencies.';
    }
}
