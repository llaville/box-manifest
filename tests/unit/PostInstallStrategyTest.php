<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Tests;

use Bartlett\BoxManifest\Composer\ManifestFactory;
use Bartlett\BoxManifest\Composer\PostInstallStrategy;

use KevinGH\Box\Configuration\Configuration;

use PHPUnit\Framework\Attributes\CoversClass;

use stdClass;

/**
 * Unit tests for PostInstallStrategy component of the Box Manifest
 *
 * @author Laurent Laville
 * @since Release 4.0.0
 */
#[CoversClass(PostInstallStrategy::class)]
final class PostInstallStrategyTest extends AbstractStrategyTestCase
{
    protected function setUp(): void
    {
        $configFilePath = __DIR__ . '/../fixtures/phario-manifest-2.0.x-dev/box.json';

        $raw = new stdClass();
        $main = 'main';
        $raw->{$main} = false;
        $config = Configuration::create($configFilePath, $raw);

        $factory = new ManifestFactory($config, true, '4.x-dev', '4.x-dev', true);
        $this->strategy = new PostInstallStrategy($factory);
    }
}
