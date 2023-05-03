<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Console\Application;
use Bartlett\BoxManifest\Console\Command\ManifestBuild;
use Bartlett\BoxManifest\Helper\BoxHelper;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

use Fidry\Console\Input\IO;

use KevinGH\Box\Configuration\Configuration;
use KevinGH\Box\Configuration\ConfigurationLoader;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Filesystem\Path;

use stdClass;
use function dirname;
use function fclose;
use function fopen;
use function realpath;
use function sprintf;
use function str_starts_with;

/**
 * Composer Plugin.
 *
 * @author Laurent Laville
 * @since Release 3.6.0
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_UPDATE_CMD => 'updateManifestAssets',
        ];
    }

    public function updateManifestAssets(Event $event): void
    {
        $composer = $event->getComposer();
        $io = $event->getIO();

        $vendorDir = $composer->getConfig()->get('vendor-dir');

        $pluginConfig = new PluginConfig($composer->getPackage()->getExtra()['box-manifest'] ?? []);

        $configFilePath = $pluginConfig->getConfigFile(dirname($vendorDir), 'box.json.dist');

        if ($io->isVeryVerbose()) {
            if (empty($configFilePath)) {
                $message = 'Updating manifest assets without BOX config file';
            } else {
                $message = sprintf('Updating manifest assets for %s BOX config file', $configFilePath);
            }
            $io->write(sprintf('<info>%s</info>', $message));
        }

        $configLoader = new ConfigurationLoader();

        if (empty($configFilePath)) {
            $raw = new stdClass();
            $main = 'main';
            $raw->{$main} = false;
            $config = Configuration::create($configFilePath, $raw);
            $map = $pluginConfig->getMap();
        } else {
            $config = $configLoader->loadFile($configFilePath);
            $map = $pluginConfig->getMap();
            if (empty($map)) {
                $map = $config->getFileMapper()->getMap();
            }
        }

        $factory = new ManifestFactory(
            $config,
            $io->isDecorated(),
            (new BoxHelper())->getBoxVersion(),
            (new Application())->getVersion()
        );

        $strategy = new DefaultStrategy($factory);

        $inputDefinition = (new ManifestBuild())->getDefinition();

        $assetCount = 0;

        foreach ($map as $mapFile) {
            foreach ($mapFile as $source => $target) {
                if (str_starts_with($target, '.box.manifests/')) {
                    $source = Path::makeAbsolute($source, dirname($vendorDir));
                    $arrayInput = new ArrayInput(['--format' => 'auto', '--output-file' => $source], $inputDefinition);
                    $boxIO = new IO($arrayInput, new NullOutput());
                    $manifest = $strategy->build(new ManifestOptions($boxIO));

                    $stream = new StreamOutput(fopen($source, 'w'));
                    $stream->setDecorated($io->isDecorated());
                    $stream->write($manifest);
                    fclose($stream->getStream());

                    if ($io->isVeryVerbose()) {
                        $message = sprintf('Writing manifest to file %s', realpath($source));
                        $io->write($message);
                        $assetCount++;
                    }
                }
            }
        }
        if ($io->isVeryVerbose()) {
            $io->write(sprintf('<info>%s assets generated for BOX Manifest</info>', ($assetCount > 0 ? $assetCount : 'None')));
        }
    }
}
