<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Fidry\Console\Input\IO;

use KevinGH\Box\Console\Command\Validate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Laurent Laville
 * @since Release 3.2.0
 */
final class BoxValidate extends Command
{
    use BoxCommandAware;

    public const NAME = 'box:validate';

    public function __construct(Validate $boxCommand)
    {
        $this->boxCommand = $boxCommand;
        parent::__construct(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $formatter = $output->getFormatter();
        $formatter->setStyle(
            'recommendation',
            new OutputFormatterStyle('black', 'yellow'),
        );

        $io = new IO($input, $output);

        return $this->boxCommand->execute($io);
    }
}
