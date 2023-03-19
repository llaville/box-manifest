<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console;

use Bartlett\BoxManifest\Helper\ManifestHelper;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Helper\HelperSet;

use Phar;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class ManifestApplication extends SymfonyApplication
{
    protected function getDefaultHelperSet(): HelperSet
    {
        $helperSet = parent::getDefaultHelperSet();
        $helperSet->set(new ManifestHelper());
        return $helperSet;
    }
}
