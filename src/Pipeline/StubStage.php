<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Bartlett\BoxManifest\Console\Logger;
use Bartlett\BoxManifest\Helper\BoxConfigurationHelper;
use Bartlett\BoxManifest\Helper\ManifestHelper;

use function array_keys;
use function trim;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final readonly class StubStage extends AbstractStage implements StageInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $payload): array
    {
        $context = ['status' => Logger::STATUS_RUNNING, 'id' => $payload['pid']];

        /** @var BoxConfigurationHelper $config */
        $config = $payload['configuration'];

        $resources = empty($payload['resources']) ? array_keys($this->getMetaData()) : $payload['resources'];

        /** @var ManifestHelper $helper */
        $helper = $this->command->getHelper(ManifestHelper::NAME);

        $stubGenerator = $helper->getStubGenerator(
            $payload['template'],
            $resources,
            $config->getMap(),
            $payload['versions']['boxManifest'] ?? '@dev',
            $payload['resourceDir']
        );

        $stub = $stubGenerator->generateStub(
            $config->getAlias(),
            $config->getBanner(),
            $config->getMainScript(),
            $config->getShebang(),
            $config->withInterceptFileFunctions(),
            $config->withCheckRequirements()
        );

        $targetFilename = $payload['outputStub'] ?? self::STDOUT;

        $this->writeToStream($targetFilename, trim($stub), 'Unable to write stub PHP code', $context);

        return $payload;
    }
}
