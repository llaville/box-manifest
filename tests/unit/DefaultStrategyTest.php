<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\DefaultStrategy;
use Bartlett\BoxManifest\Composer\ManifestBuildStrategy;
use Bartlett\BoxManifest\Composer\ManifestFactory;

use KevinGH\Box\Configuration\Configuration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

use stdClass;
use InvalidArgumentException;

/**
 * Unit tests for DefaultStrategy component of the Box Manifest
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
#[CoversClass(DefaultStrategy::class)]
final class DefaultStrategyTest extends TestCase
{
    protected ManifestBuildStrategy $strategy;

    protected function setUp(): void
    {
        $configFilePath = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev/box.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $factory = new ManifestFactory($config, true, '4.x-dev', '4.x-dev', true);
        $this->strategy = new DefaultStrategy($factory);
    }

    #[DataProviderExternal(ExternalDataProvider::class, 'recognizedFilePatterns')]
    public function testRecognizedFilePatterns(string $outputFormat, ?string $resource, bool $expectedException): void
    {
        if ($expectedException) {
            $this->expectException(InvalidArgumentException::class);
        }

        $callable = $this->strategy->getCallable($outputFormat, $resource);
        $this->assertIsCallable($callable);
    }

    #[DataProviderExternal(ExternalDataProvider::class, 'recognizedOutputFormat')]
    public function testRecognizedOutputFormat(string $outputFormat, ?string $resource, bool $expectedException): void
    {
        if ($expectedException) {
            $this->expectException(InvalidArgumentException::class);
        }

        $callable = $this->strategy->getCallable($outputFormat, $resource);
        $this->assertIsCallable($callable);
    }

    #[DataProviderExternal(ExternalDataProvider::class, 'recognizedMimeType')]
    public function testMimeTypeResource(string $resource, $mimeTypeExpected): void
    {
        $this->assertSame($mimeTypeExpected, $this->strategy->getMimeType($resource, null));
    }
}
