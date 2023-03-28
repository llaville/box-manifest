<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Fidry\Console\Command\Command;
use Fidry\Console\Input\IO;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Laurent Laville
 * @since Release 3.2.0
 */
trait BoxCommandAware
{
    protected Command $boxCommand;
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
                    )
                )
            )
            ->setHelp($configuration->getHelp())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new IO($input, $output);

        return $this->boxCommand->execute($io);
    }
}
