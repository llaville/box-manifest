<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use KevinGH\Box\Console\Command\Validate;

use Symfony\Component\Console\Command\Command;

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
}
