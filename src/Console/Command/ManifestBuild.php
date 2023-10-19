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

use Fidry\Console\Input\IO;

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
use function rtrim;
use function sprintf;
use function uniqid;
use const PHP_EOL;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class ManifestBuild extends Command
{
    public const NAME = 'manifest:build';

    private const HELP = <<<'HELP'
        The <info>%command.name%</info> command will generate a manifest of your application.
        HELP;

    private const BOOTSTRAP_OPTION = 'bootstrap';
    private const FORMAT_OPTION = 'format';
    private const SBOM_SPEC_OPTION = 'sbom-spec';
    private const OUTPUT_OPTION = 'output-file';

    protected function configure(): void
    {
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
                'Format of the output: <comment>' . implode(', ', array_column(ManifestFormat::cases(), 'value')) . '</comment>',
                ManifestFormat::auto->value
            ),
            new InputOption(
                self::SBOM_SPEC_OPTION,
                's',
                InputOption::VALUE_REQUIRED,
                'SBOM specification version: <comment>' . implode(', ', array_column(Version::cases(), 'value')) . '</comment>',
                Version::v1dot5->value
            ),
            new InputOption(
                self::OUTPUT_OPTION,
                'o',
                InputOption::VALUE_REQUIRED,
                'Write results to file (<comment>default to standard output</comment>)'
            ),
        ];

        $this->setName(self::NAME)
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

        $noDebug = $options->hasNoDebug();
        $bootstrap = $options->getBootstrap();
        $outputFile = $options->getOutputFile();
        $rawFormat = $options->getFormat(true);
        $format = $options->getFormat();
        $sbomSpec = $options->getSbomSpec();

        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelper(BoxHelper::NAME);

        if (!$noDebug) {
            $boxHelper->checkPhpSettings($io);
        }

        /** @var DebugFormatterHelper $debugFormatter */
        $debugFormatter = $this->getHelper('debug_formatter');

        $pid = uniqid();

        if ($io->isVeryVerbose() && !$noDebug) {
            $io->write(
                $debugFormatter->start($pid, sprintf('Generating manifest in %s format', $options->getFormatDisplay()), 'STARTED')
            );
        }

        if (!empty($bootstrap) && file_exists($bootstrap)) {
            if ($io->isVeryVerbose()) {
                $io->write(
                    $debugFormatter->progress($pid, sprintf('Bootstrapped file "<info>%s</info>"', $bootstrap), false, 'DEBUG')
                );
            }
            include $bootstrap;
        }

        $config = $boxHelper->getBoxConfiguration(
            $io->isVerbose() ? $io : $io->withOutput(new NullOutput()),
            true,
            $io->getOption(BoxHelper::NO_CONFIG_OPTION)->asBoolean()
        );

        $factory = new ManifestFactory($config, $output->isDecorated(), $boxHelper->getBoxVersion(), $this->getApplication()->getVersion());
        $manifest = $factory->build($options) ?? '';
        $manifest = rtrim($manifest, PHP_EOL) . PHP_EOL;

        if (empty($outputFile)) {
            $io->write($manifest);
            $message = 'Writing results to standard output';
        } else {
            $stream = new StreamOutput(fopen($outputFile, 'w'));
            if (ManifestFormat::ansi === $format) {
                $stream->setDecorated(true);
            }
            $stream->write($manifest);
            fclose($stream->getStream());

            $message = sprintf('Writing manifest to file "<comment>%s</comment>"', realpath($outputFile));
        }

        if ($io->isVeryVerbose() && !$noDebug) {
            $io->write(
                $debugFormatter->stop($pid, $message, true, 'RESPONSE')
            );
            $io->write(
                $debugFormatter->stop($pid, 'Process elapsed time ' . Helper::formatTime(microtime(true) - $startTime), true, 'FINISHED')
            );
        } elseif ($io->isVerbose()) {
            $io->comment($message);
        }

        return Command::SUCCESS;
    }
}
