<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Bartlett\BoxManifest\Helper\BoxHelper;
use Bartlett\BoxManifest\Helper\ManifestHelper;

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

use function array_merge;
use function fclose;
use function fopen;
use function microtime;
use function realpath;
use function sprintf;
use function str_replace;
use function uniqid;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class ManifestStub extends Command
{
    public const NAME = 'manifest:stub';

    private const HELP = <<<'HELP'
        The <info>%command.name%</info> command will generate a stub of your manifest application.
        HELP;

    private const TEMPLATE_OPTION = 'template';
    private const OUTPUT_OPTION = 'output-file';

    protected function configure(): void
    {
        $options = [
            new InputOption(
                self::TEMPLATE_OPTION,
                't',
                InputOption::VALUE_REQUIRED,
                'PHP template file to customize the stub'
            ),
            new InputOption(
                self::OUTPUT_OPTION,
                'o',
                InputOption::VALUE_REQUIRED,
                'Write results to file (<comment>default to standard output</comment>)'
            ),
        ];

        $this->setName(self::NAME)
            ->setDescription('Generates a stub for your manifest application.')
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

        /** @var DebugFormatterHelper $debugFormatter */
        $debugFormatter = $this->getHelper('debug_formatter');

        $pid = uniqid();

        if ($io->isVeryVerbose()) {
            $io->write(
                $debugFormatter->start($pid, 'Generating stub', 'STARTED')
            );
        }

        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelper(BoxHelper::NAME);

        /** @var ManifestHelper $manifestHelper */
        $manifestHelper = $this->getHelper(ManifestHelper::NAME);

        $stubGenerator = $manifestHelper->getStubGenerator($io->getOption(self::TEMPLATE_OPTION)->asNullableString());

        $config = $boxHelper->getBoxConfiguration(
            $io->isVerbose() ? $io : $io->withOutput(new NullOutput()),
            true,
            $io->getOption(BoxHelper::NO_CONFIG_OPTION)->asBoolean()
        );

        $configPath = $config->getConfigurationFile();

        if ($configPath) {
            $shebang = $config->getShebang();

            $banner = $config->getStubBannerContents();

            $index = $config->hasMainScript()
                ? str_replace($config->getBasePath() . '/', '', $config->getMainScriptPath())
                : null;
            $alias = $config->getAlias();

            $stub = $stubGenerator->generateStub(
                $alias,
                $banner,
                $index,
                $config->isInterceptFileFuncs(),
                $shebang,
                $config->checkRequirements()
            );
        } else {
            $stub = $stubGenerator->generateStub(null, null, null, false, null, false);
        }

        $outputFile = $io->getOption(self::OUTPUT_OPTION)->asNullableString();

        if (empty($outputFile)) {
            $output->writeln($stub);
            $message = 'Writing stub code to standard output';
        } else {
            $stream = new StreamOutput(fopen($outputFile, 'w'));
            $stream->write($stub);
            fclose($stream->getStream());

            $message = sprintf('Writing stub to file "<comment>%s</comment>"', realpath($outputFile));
        }

        if ($io->isVeryVerbose()) {
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
