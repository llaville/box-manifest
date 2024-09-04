<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Console\Application;
use Bartlett\BoxManifest\Console\Command\Make;
use Bartlett\BoxManifest\Pipeline\AbstractStage;
use Bartlett\BoxManifest\Pipeline\StageInterface;

use Composer\Script\Event;

use KevinGH\Box\Configuration\Configuration;
use KevinGH\Box\Json\Json;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

use stdClass;
use function dirname;
use function file_exists;
use function realpath;
use function str_starts_with;

/**
 * This strategy is usefully if you want to keep your manifests synchronized with Composer dependencies installed.
 *
 * @see https://getcomposer.org/doc/articles/scripts.md
 *
 * For example, on a `composer.json` file, add the following setting :
 *
 * ```json
 *   "scripts": {
 *       "post-update-cmd": "Bartlett\\BoxManifest\\Composer\\PostInstallStrategy::postUpdate"
 *   }
 * ```
 * CAUTION: Read https://github.com/composer/composer/discussions/11430 to know why you may have issue with standard Composer 2.5.5
 *
 * @author Laurent Laville
 * @since Release 3.5.0
 */
final readonly class PostInstallStrategy implements ManifestBuildStrategy
{
    public function __construct(private ManifestFactory $factory)
    {
    }

    public function getMimeType(string $resourceFile, ?string $version): string
    {
        return (new DefaultStrategy($this->factory))->getMimeType($resourceFile, $version);
    }

    public function getCallable(string $outputFormat, ?string $resourceFile): callable
    {
        return (new DefaultStrategy($this->factory))->getCallable($outputFormat, $resourceFile);
    }

    public static function postUpdate(Event $event): void
    {
        $composer = $event->getComposer();

        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require_once $vendorDir . '/autoload.php';

        $extra = $composer->getPackage()->getExtra();

        $configFilePath = $extra['box-project']['config-file'] ?? null;

        // checks if BOX config file declared exists
        if (empty($configFilePath) || !file_exists($configFilePath)) {
            // otherwise, try with root base dir package and "box.json.dist" BOX config file
            $configFilePath = dirname($vendorDir) . '/box.json.dist';
        }

        $configFilePath = realpath($configFilePath);

        if (empty($configFilePath)) {
            // nothing to do without a BOX configuration
            return;
        }

        /** @var stdClass $json */
        $json = (new Json())->decodeFile($configFilePath);

        // avoid assertion errors, because BOX checks if these entries exists
        $filesBin = 'files-bin';
        unset($json->stub, $json->{$filesBin});

        $config = Configuration::create(null, $json);

        $resourcePath = $extra['box-project']['resource-dir'] ?? AbstractStage::BOX_MANIFESTS_DIR;
        $resources = [];

        foreach ($config->getFileMapper()->getMap() as $mapFile) {
            foreach ($mapFile as $source => $target) {
                if ((dirname($target) === '.' && $resourcePath === '/') || str_starts_with($target, $resourcePath)) {
                    $resources[] = $source;
                }
            }
        }

        if (file_exists(AbstractStage::META_DATA_FILE)) {
            unlink(AbstractStage::META_DATA_FILE);
        }

        $makeCommand = new Make();
        $application = new Application();
        $application->add($makeCommand);

        $arrayInput = new ArrayInput([
            'make',
            'stages' => [StageInterface::BUILD_STAGE],
            '--output-format' => 'auto',
            '--resource' => $resources,
        ], $makeCommand->getDefinition());

        $application->run($arrayInput, new NullOutput());
    }
}
