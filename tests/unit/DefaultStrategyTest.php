<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\DefaultStrategy;
use Bartlett\BoxManifest\Composer\ManifestFactory;

use KevinGH\Box\Configuration\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;

use stdClass;
use InvalidArgumentException;

/**
 * Unit tests for DefaultStrategy component of the Box Manifest
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class DefaultStrategyTest extends TestCase
{
    #[DataProvider('dpRecognizedFilePatterns')]
    public function testRecognizedFilePatterns(string $outputFormat, ?string $resource, bool $expectedException): void
    {
        $this->testAutoDetection($outputFormat, $resource, $expectedException);
    }

    #[DataProvider('dpRecognizedOutputFormat')]
    public function testRecognizedOutputFormat(string $outputFormat, ?string $resource, bool $expectedException): void
    {
        $this->testAutoDetection($outputFormat, $resource, $expectedException);
    }

    private function testAutoDetection(string $outputFormat, ?string $resource, bool $expectedException): void
    {
        if ($expectedException) {
            $this->expectException(InvalidArgumentException::class);
        }

        $configFilePath = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev/box.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $factory = new ManifestFactory($config, true, '4.x-dev', '4.x-dev');
        $strategy = new DefaultStrategy($factory);

        $callable = $strategy->getCallable($outputFormat, $resource);
        $this->assertIsCallable($callable);
    }

    public static function dpRecognizedFilePatterns(): iterable
    {
        yield ['auto', null, false];
        yield ['auto', 'console.txt', false];
        yield ['auto', 'plain.txt', false];
        yield ['auto', 'manifest.txt', false];
        yield ['auto', 'ansi.txt', false];
        yield ['auto', 'sbom.json', false];
        yield ['auto', 'sbom.xml', false];
        yield ['auto', 'sbom.cdx.json', false];
        yield ['auto', 'sbom.cdx.xml', false];
        yield ['auto', 'custom.bin', false];
        yield ['auto', 'custom.txt', true];
    }

    public static function dpRecognizedOutputFormat(): iterable
    {
        yield ['console', null, false];
        yield ['console', 'whatever.you.want', false];

        yield ['plain', null, false];
        yield ['plain', 'whatever.you.want', false];

        yield ['ansi', null, false];
        yield ['ansi', 'whatever.you.want', false];

        yield ['sbom-json', null, false];
        yield ['sbom-json', 'whatever.you.want', false];

        yield ['sbom-xml', null, false];
        yield ['sbom-xml', 'whatever.you.want', false];

        yield ['\My\Space\ClassNotFound', null, true];
        yield ['\My\Space\ClassNotFound', 'whatever.you.want', true];
    }
}
