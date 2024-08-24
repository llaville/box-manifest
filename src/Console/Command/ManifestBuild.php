<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Bartlett\BoxManifest\Composer\ManifestFactory;
use Bartlett\BoxManifest\Composer\ManifestOptions;
use Bartlett\BoxManifest\Helper\BoxHelper;
use Bartlett\BoxManifest\Helper\ManifestFormat;

use CycloneDX\Core\Spec\Version;

use Fidry\Console\IO;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

use function array_column;
use function array_merge;
use function fclose;
use function file_exists;
use function fopen;
use function implode;
use function microtime;
use function realpath;
use function sprintf;
use function uniqid;
use const PHP_EOL;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class ManifestBuild extends Command
{
    public const NAME = 'build';

    private const HELP = <<<'HELP'
        The <info>%command.name%</info> command will generate a manifest of your application.
        HELP;

    protected function configure(): void
    {
        $options = [
            new InputOption(
                ManifestOptions::BOOTSTRAP_OPTION,
                'b',
                InputOption::VALUE_REQUIRED,
                'A PHP script that is included before execution',
            ),
            new InputOption(
                ManifestOptions::FORMAT_OPTION,
                'f',
                InputOption::VALUE_REQUIRED,
                'Format of the output: <comment>' . implode(', ', array_column(ManifestFormat::cases(), 'value')) . '</comment>',
                ManifestFormat::auto->value
            ),
            new InputOption(
                ManifestOptions::SBOM_SPEC_OPTION,
                's',
                InputOption::VALUE_REQUIRED,
                'SBOM specification version: <comment>' . implode(', ', array_column(Version::cases(), 'value')) . '</comment>',
                Version::v1dot6->value
            ),
            new InputOption(
                ManifestOptions::OUTPUT_OPTION,
                'o',
                InputOption::VALUE_REQUIRED,
                'Write results to file (<comment>default to standard output</comment>)'
            ),
        ];

        $this->setName(self::NAME)
            ->setAliases(['manifest:' . self::NAME])  // give a chance to keep migration from v3 to v4 still working (but consider it as @deprecated)
            ->setDescription('Creates a manifest of your software components and dependencies.')
            ->setDefinition(
                new InputDefinition(
                    array_merge((new BoxHelper())->getBoxConfigOptions(), $options)
                )
            )
            ->setHelp(self::HELP)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $io = new IO($input, $output);

        $options = new ManifestOptions($io);

        $bootstrap = $options->getBootstrap();
        $outputFile = $options->getOutputFile();
        $rawFormat = $options->getFormat(true);
        $format = $options->getFormat();
        $sbomSpec = $options->getSbomSpec();

        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelper(BoxHelper::NAME);
        $boxHelper->checkPhpSettings($io);

        /** @var DebugFormatterHelper $debugFormatter */
        $debugFormatter = $this->getHelper('debug_formatter');

        $pid = uniqid();

        if ($io->isVeryVerbose()) {
            $io->write(
                $debugFormatter->start(
                    $pid,
                    sprintf('Generating manifest in %s format', $options->getFormatDisplay()),
                    'STARTED'
                )
            );
        }

        if (!empty($bootstrap) && file_exists($bootstrap)) {
            if ($io->isVeryVerbose()) {
                $io->write(
                    $debugFormatter->progress(
                        $pid,
                        sprintf('Bootstrapped file "<info>%s</info>"', $bootstrap),
                        false,
                        'STARTED'
                    )
                );
            }
            include $bootstrap;
        }

        $config = $boxHelper->getBoxConfiguration(
            $io->withOutput(new NullOutput()),
            true,
            $io->getTypedOption(BoxHelper::NO_CONFIG_OPTION)->asBoolean()
        );

        $configFile = $config->getConfigurationFile();

        if (null !== $configFile && $io->isVeryVerbose()) {
            $io->write(
                $debugFormatter->progress(
                    $pid,
                    sprintf((!empty($bootstrap) ? PHP_EOL : '') . 'Loading the configuration file "<info>%s</info>"', $configFile),
                    false,
                    'STARTED'
                )
            );
        }

        $boxManifestVersion = $this->getApplication()?->getVersion() ? : '@dev';
        $factory = new ManifestFactory($config, $output->isDecorated(), $boxHelper->getBoxVersion(), $boxManifestVersion, false);
        $manifest = $factory->build($options) ?? '';

        if (empty($outputFile)) {
            $io->writeln($manifest);
            $message = 'Writing results to standard output';
        } else {
            // @phpstan-ignore argument.type
            $stream = new StreamOutput(fopen($outputFile, 'w'));
            if (ManifestFormat::consoleStyle === $format) {
                $stream->setDecorated(true);
            }
            $stream->write($manifest);
            fclose($stream->getStream());

            $message = sprintf('Writing manifest to file "<comment>%s</comment>"', realpath($outputFile));
        }

        if ($io->isVeryVerbose()) {
            $io->write(
                $debugFormatter->stop($pid, $message, true, 'RESPONSE')
            );
            $io->write(
                $debugFormatter->stop(
                    $pid,
                    'Process elapsed time ' . Helper::formatTime(microtime(true) - $startTime),
                    true,
                    'FINISHED'
                )
            );
        }

        return Command::SUCCESS;
    }
}
