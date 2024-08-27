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

if (count($argv) < 2) {
    // Print Usage.
    $logger->log('notice', 'If you want to build a manifest in specific format, please use one of these syntaxes');
    $logger->log('notice', sprintf('Usage: php %s %s :: %s', __FILE__, 'sbom-json', 'SBOM JSON format'));
    $logger->log('notice', sprintf('Usage: php %s %s :: %s', __FILE__, 'sbom-xml', 'SBOM XML format'));
    $logger->log('notice', sprintf('Usage: php %s %s :: %s', __FILE__, 'plain', 'Plain text (key: value) format'));
    $logger->log('notice', sprintf('Usage: php %s %s :: %s', __FILE__, 'console-style', 'Console Line format'));
    $logger->log('notice', sprintf('Usage: php %s %s :: %s', __FILE__, 'console-table', 'Console Table format'));
    exit(1);
}

(new PhpSettingsHandler($logger))->check();

$configLoader = new ConfigurationLoader();

$config = $configLoader->loadFile(__DIR__ . '/app-fixtures/app-fixtures.box.json');

$factory = new ManifestFactory($config, true, (new BoxHelper())->getBoxVersion(), (new Application())->getVersion(), false);

try {
    if ($argv[1] == 'sbom-json') {
        // 1.
        $specVersion = '1.4'; // Allowed values are: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6
        $result = $factory->toSbomJson($specVersion);
    } elseif ($argv[1] == 'sbom-xml') {
        // 2.
        $specVersion = '1.4'; // Allowed values are: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6
        $result = $factory->toSbomXml($specVersion);
    } elseif ($argv[1] == 'plain') {
        // 3.
        $result = $factory->toText();
    } elseif ($argv[1] == 'console-style') {
        // 4.
        $result = $factory->toHighlight();
    } elseif ($argv[1] == 'console-table') {
        // 5.
        $result = $factory->toConsole();
    }
    throw new InvalidArgumentException(sprintf('Unknown format "%s"', $argv[1]));
} catch (Throwable $e) {
} finally {
    $logger->log(
        'info',
        'Using composer.json : ' . $config->getComposerJson()?->path
    );
    $logger->log(
        'info',
        'Using composer.lock : ' . $config->getComposerLock()?->path
    );
    echo sprintf('%s%s%s', PHP_EOL, $result ?? $e->getMessage(), PHP_EOL), PHP_EOL;
}
