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
            $payload['versions']['boxManifest']
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
            $manifest = $factory->build($options);
            if (null !== $manifest) {
                if ($this->writeToStream($resourceFile, $manifest, 'Unable to write resource') === 1) {
                    $buildsCount++;

                    $resourceEnum = ManifestFile::tryFrom(basename($resourceFile)) ?? null;
                    $mimeType = match ($options->getFormat()) {
                        null, ManifestFormat::auto => match ($resourceEnum) {
                            ManifestFile::txt => 'text/plain',
                            ManifestFile::sbomXml => 'application/vnd.sbom+xml',
                            ManifestFile::sbomJson => 'application/vnd.sbom+json; version=' . $options->getSbomSpec(),
                            default => 'application/octet-stream',
                        },
                        ManifestFormat::plain => 'text/plain',
                        ManifestFormat::sbomXml => 'application/vnd.cyclonedx+xml',
                        ManifestFormat::sbomJson => 'application/vnd.cyclonedx+json',
                        default => 'application/octet-stream',
                    };

                    $payload['response']['artifacts'][$resourceFile] = $mimeType;
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
