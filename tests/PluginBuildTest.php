<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Script\ScriptEvents;

use Generator;
use function dirname;

/**
 * Unit tests for Plugin component that build assets
 *
 * @author Laurent Laville
 * @since Release 3.6.0
 */
final class PluginBuildTest extends TestCase
{
    public static function provideComposerFiles(): Generator
    {
        $pluginFixturesDir = __DIR__ . '/fixtures/plugin/';

        yield 'Without BOX Config file declared' => [
            static fn () => $pluginFixturesDir . 'box-001/composer.json',
            [],
            0
        ];

        yield 'With valid BOX Config file declared but no mapping file defined' => [
            static fn () => $pluginFixturesDir . 'box-002/composer.json',
            [],
            0
        ];

        yield 'With valid BOX Config file declared and full mapping file defined' => [
            static fn () => $pluginFixturesDir . 'box-003/composer.json',
            [
                $pluginFixturesDir . 'box-003/console.txt',
                $pluginFixturesDir . 'box-003/manifest.txt',
                $pluginFixturesDir . 'box-003/sbom.xml',
                $pluginFixturesDir . 'box-003/sbom.json'
            ],
            4
        ];

        yield 'With valid BOX Config file declared and partial mapping file re-defined' => [
            static fn () => $pluginFixturesDir . 'box-004/composer.json',
            [
                $pluginFixturesDir . 'box-004/console.txt',
                $pluginFixturesDir . 'box-004/sbom.json'
            ],
            2
        ];
    }

    /**
     * @dataProvider provideComposerFiles
     */
    public function testCreateFromComposer(callable $composerResolver, array $assetsGenerated, int $assetsCount): void
    {
        $io = new NullIO();

        $composerFile = $composerResolver();

        $composer = (new Factory())->createComposer($io, $composerFile, false, dirname($composerFile));

        $composer->getEventDispatcher()->dispatchScript(ScriptEvents::POST_UPDATE_CMD);

        $this->assertCount($assetsCount, $assetsGenerated);

        foreach ($assetsGenerated as $asset) {
            $this->assertFileExists($asset);
        }
    }
}
