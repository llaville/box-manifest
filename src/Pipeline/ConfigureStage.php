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
use RuntimeException;
use Throwable;
use function array_keys;
use function array_push;
use function file_exists;
use function file_get_contents;
use function json_decode;
use function json_encode;
use function rtrim;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final readonly class ConfigureStage extends AbstractStage implements StageInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $payload): array
    {
        $context = ['status' => Logger::STATUS_RUNNING, 'id' => $payload['pid']];

        /** @var BoxConfigurationHelper $configHelper */
        $configHelper = $payload['configuration'];

        $configPath = $configHelper->getConfigurationFile() ?? 'box.json';

        $configs = [];

        if (file_exists($configPath)) {
            try {
                /**
                 * @var array{dump-autoload?: bool, files-bin?: string[], map: string[], stub: string} $configs
                 * @phpstan-ignore argument.type
                 */
                $configs = json_decode(file_get_contents($configPath), true);
            } catch (Throwable) {
            }
        }

        // @link Due to issue https://github.com/box-project/box/issues/580
        // @see https://github.com/box-project/box/issues/580#issuecomment-2326577684

        // should be applied only if not previously defined
        // and if `exclude-dev-files` BOX directive is not defined
        // @see https://github.com/llaville/box-manifest/issues/18
        if (!isset($configs['exclude-dev-files'])) {
            $configs['dump-autoload'] ??= false;
        }

        if (!isset($configs['files-bin'])) {
            $configs['files-bin'] = [];
        }

        $resources = empty($payload['resources']) ? array_keys($this->getMetaData()) : $payload['resources'];
        $resourceDir = $payload['resourceDir'];

        array_push($configs['files-bin'], ...$resources);
        $configs['files-bin'][] = self::META_DATA_FILE;

        $mapFiles = $configHelper->getMap();

        if (!empty($resourceDir) && '/' !== $resourceDir) {
            foreach ($resources as $resource) {
                $mapFiles[] = [$resource => rtrim($resourceDir, '/') . '/' . $resource];
            }
        }
        if (!empty($mapFiles)) {
            $configs['map'] = $mapFiles;
        }

        if (self::STDOUT !== $payload['outputStub']) {
            $configs['stub'] = $payload['outputStub'];
        }

        $data = json_encode($configs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (!$data) {
            $context['error'] = true;
            $message = 'Unable to encode BOX configuration data';
            $this->logger->error($message, $context);
            throw new RuntimeException($message);
        }

        $targetFilename = $payload['outputConf'] ?? self::STDOUT;

        $this->writeToStream($targetFilename, $data, 'Unable to write BOX configuration', $context);

        return $payload;
    }
}
