<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Composer\Pcre\Preg;
use Composer\XdebugHandler\Process;
use Composer\XdebugHandler\XdebugHandler;

use function count;
use function explode;
use function file_put_contents;
use function getenv;
use function in_array;
use function ini_get;
use function phpversion;
use function str_replace;
use function version_compare;
use const PHP_EOL;

/**
 * Restarts with xdebug loaded in coverage mode, by uncommenting xdebug in the
 * temporary ini file and setting the XDEBUG_MODE environment variable,
 * and with phar.read_only=0 (disabled).
 *
 * Credits to @link https://github.com/composer/xdebug-handler/blob/3.0.5/tests/App/README.md
 * that help to make a compatible version between
 * - https://github.com/composer/xdebug-handler/blob/3.0.5/tests/App/README.md#app-extend-mode
 * - https://github.com/composer/xdebug-handler/blob/3.0.5/tests/App/README.md#app-extend-ini
 *
 * @since Release 4.0.0
 */
class RestartHandler extends XdebugHandler
{
    /** @noinspection PhpVoidFunctionResultUsedInspection */
    protected function requiresRestart(bool $default): bool
    {
        if ($default) {
            $version = (string) phpversion('xdebug');

            if (version_compare($version, '3.1', '>=')) {
                /**
                 * @var string[] $modes
                 * @link https://xdebug.org/docs/code_coverage#xdebug_info
                 */
                $modes = xdebug_info('mode');
                return !in_array('coverage', $modes, true);
            }

            // See if xdebug.mode is supported in this version
            $iniMode = ini_get('xdebug.mode');
            if ($iniMode === false) {
                return false;
            }

            // Environment value wins but cannot be empty
            $envMode = (string) getenv('XDEBUG_MODE');
            if ($envMode !== '') {
                $mode = $envMode;
            } else {
                $mode = $iniMode !== '' ? $iniMode : 'off';
            }

            // An empty comma-separated list is treated as mode 'off'
            if (Preg::isMatch('/^,+$/', str_replace(' ', '', $mode))) {
                $mode = 'off';
            }

            $modes = explode(',', str_replace(' ', '', $mode));
            return !in_array('coverage', $modes, true);
        }

        return false;
    }

    protected function restart(array $command): void
    {
        // uncomment last xdebug line
        $regex = '/^;\s*(zend_extension\s*=.*xdebug.*)$/mi';
        $content = (string) file_get_contents((string) $this->tmpIni);

        if (Preg::isMatchAll($regex, $content, $matches)) {
            $index = count($matches[1]) - 1;
            $line = $matches[1][$index];
            $content .= $line . PHP_EOL;
        }

        $content .= 'phar.readonly = 0' . PHP_EOL;

        file_put_contents((string) $this->tmpIni, $content);
        Process::setEnv('XDEBUG_MODE', 'coverage');

        parent::restart($command);
    }
}
