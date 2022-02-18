<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

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
        $this->markAsSkippedIfPharReadonlyIsOn();

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
        $configFilePath = __DIR__ . '/fixtures/phario-manifest-2.0.x-dev/box.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $manifest = ManifestFactory::create(SimpleTextManifestBuilder::class, $config, $this->box);
        $this->assertIsString($manifest);

        $dependencies = explode(PHP_EOL, $manifest);
        $this->assertSame('phar-io/manifest: 2.0.x-dev@97803ec', $dependencies[0]);
    }
}
