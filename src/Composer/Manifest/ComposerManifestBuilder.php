<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

use RuntimeException;

/**
 * @author Laurent Laville
 * @since Release 4.4.0
 */
final class ComposerManifestBuilder implements ManifestBuilderInterface
{
    public function __construct(private readonly string $format)
    {
    }

    public function __invoke(array $content): string
    {
        $finder = new ExecutableFinder();

        $composerBinary = $finder->find('composer');
        if (null === $composerBinary) {
            throw new RuntimeException('Composer binary not found');
        }

        $composer = new Process([$composerBinary, 'show', '--tree', '--format=' . $this->format]);
        $composer->run();

        if (!$composer->isSuccessful()) {
            throw new ProcessFailedException($composer);
        }

        return $composer->getOutput();
    }
}
