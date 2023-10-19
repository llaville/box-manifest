<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\Manifest\DecorateTextManifestBuilder;
use Bartlett\BoxManifest\Composer\Manifest\SimpleTextManifestBuilder;
use Bartlett\BoxManifest\Composer\ManifestFactory;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\Configuration;
use KevinGH\Box\Test\RequiresPharReadonlyOff;

use Phar;
use stdClass;
use function explode;
use const PHP_EOL;

/**
 * Unit tests for ManifestFactory component of the Box Manifest
 *
 * @author Laurent Laville
 */
final class ManifestFactoryTest extends TestCase
{
    use RequiresPharReadonlyOff;

    private Box $box;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->box = Box::create('/tmp/box-manifest/test.phar');
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (false !== $pharPath = $this->box->getPhar()->getRealPath()) {
            Phar::unlinkArchive($pharPath);
        }

        unset($this->box);
    }

    /**
     * Creates a basic simple text (key-value pairs) manifest string.
     */
    public function testCreateSimpleTextManifest(): void
    {
        $configFilePath = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev/box.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $manifest = ManifestFactory::create(SimpleTextManifestBuilder::class, $config, true);
        $this->assertIsString($manifest);

        $dependencies = explode(PHP_EOL, $manifest);
        $this->assertSame('phar-io/manifest: 2.0.x-dev@97803ec', $dependencies[0]);
    }

    /**
     * Creates a NULL manifest, because vendor/package is not yet installed
     * (composer.json and/or composer.lock does not exist).
     * @link https://github.com/llaville/box-manifest/issues/4
     */
    public function testBuildArtifactWithoutManifest(): void
    {
        $configFilePath = __DIR__ . '/../fixtures/vendor-package-not-yet-released/box.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $manifest = ManifestFactory::create(SimpleTextManifestBuilder::class, $config, true);
        $this->assertNull($manifest);
    }

    /**
     * Creates a NULL manifest, because vendor/package has none public releases yet.
     * @link https://github.com/llaville/box-manifest/issues/5
     */
    public function testBuildSimpleManifestOnPackageWithoutPublicReleases(): void
    {
        $configFilePath = __DIR__ . '/../fixtures/vendor-package-no-public-releases/box-metadata-simpletext.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $manifest = ManifestFactory::create(SimpleTextManifestBuilder::class, $config, true);
        $this->assertIsString($manifest);

        $dependencies = explode(PHP_EOL, $manifest);
        $this->assertSame('bartlett/sandboxes: 1.0.0+no-version-set', $dependencies[0]);
    }

    /**
     * Creates a NULL manifest, because vendor/package has none public releases yet.
     * @link https://github.com/llaville/box-manifest/issues/5#issuecomment-1434308094
     */
    public function testBuildDecoratedManifestOnPackageWithoutPublicReleases(): void
    {
        $configFilePath = __DIR__ . '/../fixtures/vendor-package-no-public-releases/box-metadata-decoratetext.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $manifest = ManifestFactory::create(DecorateTextManifestBuilder::class, $config, true);
        $this->assertIsString($manifest);

        $dependencies = explode(PHP_EOL, $manifest);
        $this->assertSame('bartlett/sandboxes: <info>1.0.0+no-version-set</info>', $dependencies[0]);
    }
}
