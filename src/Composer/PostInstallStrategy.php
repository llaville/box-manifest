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

use Composer\Script\Event;

use Fidry\Console\IO;

use KevinGH\Box\Configuration\ConfigurationLoader;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\StreamOutput;

use function dirname;
use function fclose;
use function file_exists;
use function fopen;
use function realpath;
use function sprintf;
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

    public function build(ManifestOptions $options): ?string
    {
        return (new DefaultStrategy($this->factory))->build($options);
    }

    public static function postUpdate(Event $event): void
    {
        $composer = $event->getComposer();

        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require_once $vendorDir . '/autoload.php';

        $extra = $composer->getPackage()->getExtra();

        $configFilePath = $extra['box-project']['config-file'] ?? null;

        // checks if BOX config file declared exists
        if (!empty($configFilePath) && file_exists($configFilePath)) {
            $configFilePath = realpath($configFilePath) ? : null;
        } else {
            // otherwise, try with root base dir package and "box.json.dist" BOX config file
            $configFilePath = dirname($vendorDir) . '/box.json.dist';
        }

        $configLoader = new ConfigurationLoader();
        $config = $configLoader->loadFile($configFilePath);

        $io = $event->getIO();

        $factory = new ManifestFactory(
            $config,
            $io->isDecorated(),
            (new BoxHelper())->getBoxVersion(),
            (new Application())->getVersion()
        );

        $strategy = new self($factory);

        $inputDefinition = (new ManifestBuild())->getDefinition();

        $map = $config->getFileMapper()->getMap();

        foreach ($map as $mapFile) {
            foreach ($mapFile as $source => $target) {
                if (str_starts_with($target, '.box.manifests/')) {
                    $arrayInput = new ArrayInput(['--format' => 'auto', '--output-file' => $source], $inputDefinition);
                    $boxIO = new IO($arrayInput, new NullOutput());
                    $manifest = $strategy->build(new ManifestOptions($boxIO));

                    $resource = fopen($source, 'w');
                    if (!$resource) {
                        $message = sprintf('- Unable to write manifest to file "<comment>%s</comment>"', realpath($source));
                        $io->writeError($message);
                        continue;
                    }

                    if (empty($manifest)) {
                        $message = sprintf('- No manifest contents for file "<comment>%s</comment>"', realpath($source));
                        $io->writeError($message);
                        continue;
                    }

                    $stream = new StreamOutput($resource);
                    $stream->setDecorated($io->isDecorated());
                    $stream->write($manifest);
                    fclose($stream->getStream());

                    $message = sprintf('- Writing manifest to file "<comment>%s</comment>"', realpath($source));
                    $io->write($message);
                }
            }
        }
    }
}
