<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Bartlett\BoxManifest\Composer\ManifestFactory;
use Bartlett\BoxManifest\Composer\ManifestOptions;
use Bartlett\BoxManifest\Helper\ManifestFile;
use Bartlett\BoxManifest\Helper\ManifestFormat;

use Symfony\Component\Console\Input\ArrayInput;

use function serialize;
use function sprintf;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final readonly class BuildStage extends AbstractStage implements StageInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $payload): array
    {
        $this->debugPrintStage(sprintf('%s is running', __CLASS__));

        $factory = new ManifestFactory(
            $payload['configuration'],
            $payload['ansiSupport'],
            $payload['versions']['box'],
            $payload['versions']['boxManifest'],
            $payload['immutableCopy']
        );

        $buildsCount = 0;

        foreach ($payload['resources'] as $resourceFile) {
            $resourceFormat = ManifestFile::custom->value === $resourceFile ? $payload['outputFormat'] : 'auto';
            $input = new ArrayInput(
                [
                    'command' => $this->command->getName(),
                    'stages' => [StageInterface::BUILD_STAGE],
                    '--output-format' => $resourceFormat,
                    '--output-file' => $resourceFile,
                    '--sbom-spec' => $payload['sbomSpec'],
                ],
                $this->command->getDefinition()
            );
            $io = $this->io->withInput($input);
            $options = new ManifestOptions($io);
            $manifestContents = $factory->build($options);
            if (null !== $manifestContents) {
                if ($this->writeToStream($resourceFile, $manifestContents, 'Unable to write resource') === 1) {
                    $buildsCount++;
                    $payload['response']['artifacts'][$resourceFile] = $factory->getMimeType($resourceFile, $options->getSbomSpec());
                }
            }
        }

        $manifests = serialize($payload['response']['artifacts'] ?? []);
        $this->writeToStream('.box.manifests.bin', $manifests, 'Unable to write Manifests Metadata');

        $this->debugPrintStage(
            sprintf(
                '%d manifest%s built',
                $buildsCount,
                $buildsCount > 1 ? 's were' : ' was',
            )
        );

        return $payload;
    }
}
