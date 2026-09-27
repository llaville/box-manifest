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
    ->setRules([
        '@PER-CS' => true,
        'header_comment' => ['header' => $header, 'comment_type' => 'PHPDoc'],
        'blank_line_after_opening_tag' => true,
        'no_empty_statement' => false,
        'no_extra_blank_lines' => true,
        'single_line_empty_body' => false,
    ])
    ->setFinder($finder)
;
