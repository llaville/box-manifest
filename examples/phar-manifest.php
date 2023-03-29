<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once  dirname(__DIR__) . '/vendor/autoload.php';

use Bartlett\BoxManifest\Composer\ManifestFactory;
use Bartlett\BoxManifest\Console\Application;
use Bartlett\BoxManifest\Helper\BoxHelper;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\ConfigurationLoader;
use KevinGH\Box\Console\Php\PhpSettingsHandler;

use Psr\Log\AbstractLogger;

/**
 * Script that may build manifest in different formats :
 * - SBOM JSON or XML format following specification 1.1, 1.2, 1.3, or 1.4
 * - Simple key-value pairs TEXT format without decoration
 * - key-value pairs TEXT format with ansi decoration
 *
 * @author Laurent Laville
 * @since Release 3.0.0
 */

$logger = new class extends AbstractLogger {
    public function log($level, string|Stringable $message, array $context = []): void
    {
        echo sprintf('[%s] %s%s', $level, $message, PHP_EOL);
    }
};

(new PhpSettingsHandler($logger))->check();

$configLoader = new ConfigurationLoader();

$config = $configLoader->loadFile(__DIR__ . '/app-fixtures/app-fixtures-box.json');
$box = Box::create($config->getTmpOutputPath());

$factory = new ManifestFactory($config, $box, (new BoxHelper())->getBoxVersion(), true, (new Application())->getVersion());

try {
    // 1.
    $format = 'xml'; // Allowed values are: xml, json
    $specVersion = '1.4'; // Allowed values are: 1.1, 1.2, 1.3, 1.4
    $result = $factory->toSbom($format, $specVersion);

    // 2.
    //$result = $factory->toText();

    // 3.
    //$result = $factory->toHighlight();
} catch (Throwable $e) {
    echo $e->getMessage(), PHP_EOL;
} finally {
    $logger->log(
        'info',
        'Using composer.json : ' . $config->getComposerJson()
    );
    $logger->log(
        'info',
        'Using composer.lock : ' . $config->getComposerLock()
    );
    echo $result, PHP_EOL;
}
