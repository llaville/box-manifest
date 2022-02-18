<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

use function implode;
use function sprintf;
use function substr;
use const PHP_EOL;

/**
 * @author Laurent Laville
 */
final class SimpleTextManifestBuilder implements ManifestBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(array $content): string
    {
        $installedPhp = $content['installed.php'];
        $rootPackage = $installedPhp['root'];
        $entries = [];

        if (empty($rootPackage['aliases'])) {
            $version = $rootPackage['pretty_version'];
        } else {
            $version = sprintf(
                '%s@%s',
                $rootPackage['aliases'][0],
                substr($rootPackage['reference'], 0, 7)
            );
        }

        $entries[] = sprintf('%s: %s', $rootPackage['name'], $version);

        foreach ($installedPhp['versions'] as $package => $values) {
            if ($package === $rootPackage['name']) {
                // does not include root package
                continue;
            }
            if (isset($values['pretty_version'])) {
                $entries[] = sprintf('%s: %s', $package, $values['pretty_version']);
            } // otherwise, it's a virtual package
        }

        return implode(PHP_EOL, $entries);
    }
}
