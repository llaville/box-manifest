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
use Bartlett\BoxManifest\Console\Logger;

use KevinGH\Box\Configuration\Configuration;

use Symfony\Component\Console\Input\ArrayInput;

use function array_keys;
use function array_merge;
use function implode;
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
        $context = ['status' => Logger::STATUS_RUNNING, 'id' => $payload['pid']];

        $factory = new ManifestFactory(
            Configuration::create(null, $payload['configuration']->dump()),
            $payload['ansiSupport'],
            $payload['versions']['box'],
            $payload['versions']['boxManifest'],
            $payload['immutableCopy']
        );

        $buildsCount = 0;

        foreach ($payload['resources'] as $resourceFile) {
            $input = new ArrayInput(
                [
                    'command' => $this->command->getName(),
                    'stages' => [StageInterface::BUILD_STAGE],
                    '--output-format' => $payload['outputFormat'],
                    '--output-file' => $resourceFile,
                    '--sbom-spec' => $payload['sbomSpec'],
                ],
                $this->command->getDefinition()
            );
            $io = $this->io->withInput($input);
            $options = new ManifestOptions($io);
            $manifestContents = $factory->build($options);
            if (null !== $manifestContents) {
                if ($this->writeToStream($resourceFile, $manifestContents, 'Unable to write resource', $context) === 1) {
                    $buildsCount++;
                    $payload['outputs']['resources'][$resourceFile] = $factory->getMimeType($resourceFile, $options->getSbomSpec());
                }
            }
        }

        $manifests = serialize(array_merge($this->getMetaData(), $payload['outputs']['resources'] ?? []));
        $this->writeToStream(self::META_DATA_FILE, $manifests, 'Unable to write Manifests Metadata', $context);

        $this->logger->notice(
            sprintf(
                '%d manifest%s built %s',
                $buildsCount,
                $buildsCount > 1 ? 's were' : ' was',
                $buildsCount > 0 ? '>> ' . implode(', ', array_keys($payload['outputs']['resources'] ?? [])) : ''
            ),
            $context
        );

        return $payload;
    }
}
