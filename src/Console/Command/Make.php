<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Bartlett\BoxManifest\Composer\ManifestOptions;
use Bartlett\BoxManifest\Helper\BoxHelper;
use Bartlett\BoxManifest\Helper\ManifestFormat;
use Bartlett\BoxManifest\Pipeline\BuildStage;
use Bartlett\BoxManifest\Pipeline\CompileStage;
use Bartlett\BoxManifest\Pipeline\ConfigureStage;
use Bartlett\BoxManifest\Pipeline\StageInterface;
use Bartlett\BoxManifest\Pipeline\StubStage;

use CycloneDX\Core\Spec\Version;

use Fidry\Console\IO;

use InvalidArgumentException;
use League\Pipeline\PipelineBuilder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

use function class_exists;
use function count;
use function is_file;
use function is_string;
use function microtime;
use function realpath;
use function sprintf;
use function uniqid;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class Make extends Command
{
    public const NAME = 'make';

    private const HELP = <<<'HELP'
        The <info>%command.name%</info> command will create a full automated execution patterns of your manifest build process.
        HELP;

    protected function configure(): void
    {
        $options = [
            new InputArgument(
                'stages',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'Stages to proceed in your pipeline',
            ),
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
            new InputOption(
                ManifestOptions::OUTPUT_STUB_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Write stub PHP code to file (<comment>default to standard output</comment>)'
            ),
            new InputOption(
                ManifestOptions::OUTPUT_CONF_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Write BOX configuration to file (<comment>default to standard output</comment>)'
            ),
            new InputOption(
                ManifestOptions::TEMPLATE_OPTION,
                't',
                InputOption::VALUE_REQUIRED,
                'PHP template file to customize the stub'
            ),
            new InputOption(
                ManifestOptions::RESOURCE_OPTION,
                'r',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'File(s) generated by the box-manifest binary command'
            ),
            new InputOption(
                ManifestOptions::RESOURCE_DIR_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Directory where to store your manifest files into the PHP Archive',
                '.box.manifests/'
            ),
            new InputOption(
                ManifestOptions::IMMUTABLE_OPTION,
                null,
                InputOption::VALUE_NONE,
                'Generates immutable version of a manifest file',
            ),
        ];

        $this->setName(self::NAME)
            ->setDescription('Create a pipeline of your manifest build process.')
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

        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelper(BoxHelper::NAME);

        $io = new IO($input, $output);

        $options = new ManifestOptions($io);

        $resources = $io->getTypedOption(ManifestOptions::RESOURCE_OPTION)->asNonEmptyStringList();

        $bootstrap = $options->getBootstrap() ?? '';

        /** @var DebugFormatterHelper $debugFormatter */
        $debugFormatter = $this->getHelper('debug_formatter');

        $pid = uniqid();

        if ($io->isDebug()) {
            $resourceCount = count($resources);
            $io->write(
                $debugFormatter->start(
                    $pid,
                    sprintf('Making manifests for %d resource%s', $resourceCount, $resourceCount > 1 ? 's' : ''),
                    'STARTED'
                )
            );
        }

        $bootstrap = realpath($bootstrap);

        if (is_string($bootstrap) && is_file($bootstrap)) {
            if ($io->isDebug()) {
                $io->write(
                    $debugFormatter->progress(
                        $pid,
                        sprintf('Bootstrapped file "<info>%s</info>"', $bootstrap),
                        false,
                        'IN'
                    )
                );
            }
            include $bootstrap;
        }

        $pipelineBuilder = new PipelineBuilder();

        foreach ($io->getTypedArgument(ManifestOptions::STAGES_OPTION)->asNonEmptyStringList() as $stageName) {
            try {
                $stage = match ($stageName) {
                    StageInterface::BUILD_STAGE => new BuildStage($io, $this, ['pid' => $pid]),
                    StageInterface::STUB_STAGE => new StubStage($io, $this, ['pid' => $pid]),
                    StageInterface::CONFIGURE_STAGE => new ConfigureStage($io, $this, ['pid' => $pid]),
                    StageInterface::COMPILE_STAGE => new CompileStage($io, $this, ['pid' => $pid]),
                    default => $this->getCustomStage($stageName, $io),
                };
            } catch (InvalidArgumentException $e) {
                $io->error($e->getMessage());
                return Command::FAILURE;
            }

            $pipelineBuilder->add($stage);
        }

        $config = $boxHelper->getBoxConfiguration(
            $io->withOutput(new NullOutput()),
            true,
            $io->getTypedOption(BoxHelper::NO_CONFIG_OPTION)->asBoolean()
        );

        $templatePath = $io->getTypedOption(ManifestOptions::TEMPLATE_OPTION)->asNullableString()
            ?? dirname(__DIR__, 3) . '/resources/default_stub.template'
        ;

        $payload = [
            'configuration' => $config,
            'ansiSupport' => $output->isDecorated(),
            'immutableCopy' => $io->getTypedOption(ManifestOptions::IMMUTABLE_OPTION)->asBoolean(),
            'versions' => [
                'box' => $boxHelper->getBoxVersion(),
                'boxManifest' => $this->getApplication()?->getVersion() ? : '@dev',
            ],
            'template' => $templatePath,
            'resources' => $resources,
            'map' => $config->getFileMapper()->getMap(),
            'resourceDir' => $io->getTypedOption(ManifestOptions::RESOURCE_DIR_OPTION)->asNullableString(),
            'sbomSpec' => (new ManifestOptions($io))->getSbomSpec(),
            'outputFormat' => (new ManifestOptions($io))->getFormat(true),
            'output' => (new ManifestOptions($io))->getOutputFile() ?? 'php://stdout',
            'outputStub' => (new ManifestOptions($io))->getOutputStubFile(),
            'outputConf' => (new ManifestOptions($io))->getOutputConfFile(),
            'configurationFile' => $config->getConfigurationFile(),
        ];

        $pipeline = $pipelineBuilder->build();
        $finalPayload = $pipeline($payload);

        if ($io->isDebug()) {
            if (isset($finalPayload['response'])) {
                $artifacts = \implode(', ', $finalPayload['response']['artifacts']);
                $io->write(
                    $debugFormatter->stop($pid, sprintf('Following artifacts were created: %s', $artifacts), true, 'RESPONSE')
                );
            }
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

    private function getCustomStage(string $stageClass, IO $io): StageInterface
    {
        if (!class_exists($stageClass)) {
            throw new InvalidArgumentException(
                sprintf('No stage found for "%s".', $stageClass)
            );
        }

        $stage = new $stageClass($io, $this);

        if (!$stage instanceof StageInterface) {
            throw new InvalidArgumentException(
                sprintf('Stage class "%s" does not implement "%s".', $stageClass, StageInterface::class)
            );
        }

        return $stage;
    }
}
