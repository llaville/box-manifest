<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\BoxManifest\Pipeline\AbstractStage;
use Bartlett\BoxManifest\Pipeline\StageInterface;

/**
 * Example of a custom stage for pipeline of the "make" command.
 *
 * @link https://github.com/thephpleague/pipeline
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final readonly class MyCustomStage extends AbstractStage implements StageInterface
{
    public function __invoke(array $payload): array
    {
        $this->io->writeln([
            'Payload :',
            var_export($payload, true),
            sprintf('"%s" was invoked with previous payload from command "%s"', __CLASS__,  $this->command->getName())
        ]);

        return $payload;
    }
}
