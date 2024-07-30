<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\BoxManifest\Helper\BoxHelper;

use Fidry\Console\IO;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__ . '/autoload.php';

/**
 * Bootstrapping for PHPUnit
 *
 * No longer require `phar.readonly` to be off
 */
$input = new ArrayInput([]);
$output = new ConsoleOutput();
$io = new IO($input, $output);

$boxHelper = new BoxHelper();
$boxHelper->checkPhpSettings($io);
