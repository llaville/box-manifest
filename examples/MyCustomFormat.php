<?php

declare(strict_types=1);

/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

class MyCustomFormat implements ManifestBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $content): string
    {
        return var_export($content['installed.php'], true);
    }
}
