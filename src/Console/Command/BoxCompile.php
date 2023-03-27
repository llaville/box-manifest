<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Fidry\Console\Input\IO;

use KevinGH\Box\Console\Command\Compile;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function array_merge;
use function file_exists;

/**
 * @author Laurent Laville
 * @since Release 3.2.0
 */
final class BoxCompile extends Command
{
    public const NAME = 'box:compile';

    private Compile $boxCommand;

    public function __construct(Compile $boxCommand)
    {
        $this->boxCommand = $boxCommand;
        parent::__construct(self::NAME);
    }

    protected function configure(): void
    {
        $configuration = $this->boxCommand->getConfiguration();

        $this->setName(self::NAME)
            ->setDescription($configuration->getDescription())
            ->setDefinition(
                new InputDefinition(
                    array_merge(
                        $configuration->getArguments(),
                        $configuration->getOptions(),
                        [
                            new InputOption(
                                'bootstrap',
                                null,
                                InputOption::VALUE_REQUIRED,
                                'A PHP script that is included before execution',
                            )
                        ]
                    )
                )
            )
            ->setHelp($configuration->getHelp())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->hasOption('bootstrap')) {
            $filename = $input->getOption('bootstrap');
            if ($filename && file_exists($filename)) {
                include_once $filename;
            }
        }

        $formatter = $output->getFormatter();
        $formatter->setStyle(
            'recommendation',
            new OutputFormatterStyle('black', 'yellow'),
        );
        $formatter->setStyle(
            'warning',
            new OutputFormatterStyle('white', 'red'),
        );

        $io = new IO($input, $output);

        return $this->boxCommand->execute($io);
    }
}
