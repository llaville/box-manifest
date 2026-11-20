<?php

declare(strict_types=1);

/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<EOF
This file is part of the BoxManifest package.

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

// 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
$finder = Finder::create();
$finder
// 💡 additional files, eg bin entry file
// ->append([__DIR__.'/bin-entry-file'])
// 💡 folders to exclude, if any
// ->exclude([/* ... */])
// 💡 path patterns to exclude, if any
// ->notPath([/* ... */])
// 💡 extra configs
// ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
// ->ignoreVCS(true) // true by default
// 💡 root folder to check
    ->in(dirname(__DIR__, 2))
;

return (new Config())
    ->setRiskyAllowed(false)
    // allow to run on unsupported PHP Versions
    // {@link https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/README.md#supported-php-versions}
    // available since PHP-CS-Fixer 3.76.0
    // use instead `PHP_CS_FIXER_IGNORE_ENV` env var for older versions
    ->setUnsupportedPhpVersionAllowed(true)
    // use default @link https://www.php-fig.org/per/coding-style/
    ->setRules([
        '@PER-CS' => true,
        'header_comment' => ['header' => $header, 'comment_type' => 'PHPDoc'],
        'blank_line_after_opening_tag' => true,
        // @caution: it's supposed to be the default config value
        // @see https://cs.symfony.com/doc/rules/function_notation/function_declaration.html
        'function_declaration' => ['closure_fn_spacing' => 'one'],
        'no_empty_statement' => false,
        'no_extra_blank_lines' => true,
        'single_line_empty_body' => false,
    ])
    ->setFinder($finder)
;
