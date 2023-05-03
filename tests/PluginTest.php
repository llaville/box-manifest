<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\Plugin;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Plugin\PluginInterface;

use function class_exists;
use function class_implements;
use function dirname;

/**
 * Unit tests for Plugin component of the Box Manifest
 *
 * @author Laurent Laville
 * @since Release 3.6.0
 */
final class PluginTest extends TestCase
{
    private Composer $composer;

    private IOInterface $io;

    protected function setUp(): void
    {
        $this->io = new NullIO();
        $this->composer = (new Factory())->createComposer(
            $this->io,
            Factory::getComposerFile(),
            false,
            dirname(__DIR__)
        );
        parent::setUp();
    }

    /**
     * @see https://getcomposer.org/doc/articles/plugins.md#plugin-package
     */
    public function testPackageIsComposerPlugin(): void
    {
        $this->assertSame('composer-plugin', $this->composer->getPackage()->getType());
    }

    /**
     * @see https://getcomposer.org/doc/articles/plugins.md#plugin-package
     */
    public function testPluginIsRegistered(): string
    {
        $pluginClass = $this->composer->getPackage()->getExtra()['class'];

        $this->assertTrue(class_exists($pluginClass), "class '$pluginClass' does not exist");
        $this->assertSame(Plugin::class, $pluginClass);

        return $pluginClass;
    }

    /**
     * @depends testPluginIsRegistered
     */
    public function testPluginImplementsRequiredInterfaces(string $pluginClass): PluginInterface
    {
        $implements = class_implements($pluginClass);

        $this->assertContains(PluginInterface::class, $implements);

        return new $pluginClass();
    }

    /**
     * @depends testPluginImplementsRequiredInterfaces
     */
    public function testActivatePlugin(PluginInterface $plugin): PluginInterface
    {
        $plugin->activate($this->composer, $this->io);

        return $plugin;
    }

    /**
     * @depends testActivatePlugin
     */
    public function testDeactivatePlugin(PluginInterface $plugin): PluginInterface
    {
        $plugin->deactivate($this->composer, $this->io);

        return $plugin;
    }

    /**
     * @depends testDeactivatePlugin
     */
    public function testUninstallPlugin(PluginInterface $plugin): PluginInterface
    {
        $plugin->uninstall($this->composer, $this->io);

        return $plugin;
    }
}
