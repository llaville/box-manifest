<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\ManifestBuildStrategy;

use PHPUnit\Framework\Attributes\DataProviderExternal;

use InvalidArgumentException;

/**
 * Unit tests for common DefaultStrategy and PostInstallStrategy component of the Box Manifest
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
abstract class AbstractStrategyTestCase extends TestCase
{
    protected ManifestBuildStrategy $strategy;

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
