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

use Symfony\Component\Console\Input\ArrayInput;

use function array_merge;
use function file_exists;
use function file_get_contents;
use function serialize;
use function sprintf;
use function unserialize;

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

        if (file_exists(self::META_DATA_FILE)) {
            // @phpstan-ignore argument.type
            $metadata = unserialize(file_get_contents(self::META_DATA_FILE));
        } else {
            $metadata = [];
        }

        $manifests = serialize(array_merge($metadata, $payload['response']['artifacts'] ?? []));
        $this->writeToStream(self::META_DATA_FILE, $manifests, 'Unable to write Manifests Metadata');

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
