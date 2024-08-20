<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Bartlett\BoxManifest\Helper\ManifestHelper;

use function str_replace;

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
        $config = $payload['configuration'];

        /** @var ManifestHelper $helper */
        $helper = $this->command->getHelper(ManifestHelper::NAME);

        $stubGenerator = $helper->getStubGenerator(
            $payload['template'],
            $payload['resources'],
            $payload['map'],
            $payload['versions']['boxManifest']
        );

        if ($config->getConfigurationFile()) {
            /** @var null|non-empty-string $shebang */
            $shebang = $config->getShebang();

            $index = $config->hasMainScript()
                ? str_replace($config->getBasePath() . '/', '', $config->getMainScriptPath())
                : null;

            $stub = $stubGenerator->generateStub(
                $config->getAlias(),
                $config->getStubBannerContents(),
                $index,
                $config->isInterceptFileFuncs(),
                $shebang,
                $config->checkRequirements()
            );
        } else {
            $stub = $stubGenerator->generateStub(null, null, null, false, null, false);
        }

        $targetFilename = $payload['outputStub'] ?? self::STDOUT;

        $this->writeToStream($targetFilename, $stub, 'Unable to write stub PHP code');

        return $payload;
    }
}
