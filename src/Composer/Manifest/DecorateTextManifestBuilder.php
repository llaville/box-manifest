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
use function phpversion;
use function sprintf;
use function substr;
use const PHP_EOL;

/**
 * @author Laurent Laville
 * @since Release 2.1.0
 */
class DecorateTextManifestBuilder implements ManifestBuilderInterface
{
    private string $prefix;

    public function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(array $content): string
    {
        $composerJson = $content['composer.json'];
        $installedPhp = $content['installed.php'];
        $rootPackage = $installedPhp['root'];
        $reference = $rootPackage['reference'] ?? '';
        $entries = [];

        if (isset($rootPackage['pretty_version'])) {
            $version = sprintf(
                '%s%s',
                $rootPackage['pretty_version'],
                empty($reference) ? '' : '@' . substr($reference, 0, 7)
            );
        } else {
            $version = $rootPackage['version'];
        }
        $entries[] = sprintf('%s: <info>%s</info>', $rootPackage['name'], $version);

        $allRequirements = [
            '' => $composerJson['require'],
            ' (for development)' => $composerJson['require-dev'] ?? [],
        ];

        foreach ($allRequirements as $category => $requirements) {
            foreach ($requirements as $req => $constraint) {
                $prefix = $this->prefix . ' ';
                if (!empty($constraint)) {
                    $constraint = sprintf('<comment>%s</comment>', $constraint);
                    $prefix .= 'requires';
                } else {
                    $prefix .= 'uses';
                }
                $installedPhp['versions'][$req]['prefix'] = $prefix;
                /** @var string $constraint */
                if ('php' === $req) {
                    $entries[] = sprintf('%s%s %s: <info>%s</info>', $prefix, $category, "$req $constraint", phpversion());
                } elseif (str_starts_with($req, 'ext-')) {
                    $extension = substr($req, 4);
                    $entries[] = sprintf('%s%s %s: <info>%s</info>', $prefix, $category, "$req $constraint", phpversion($extension));
                } else {
                    $installedPhp['versions'][$req]['constraint'] = $constraint;
                    $installedPhp['versions'][$req]['category'] = $category;
                }
            }
        }

        foreach ($installedPhp['versions'] as $package => $values) {
            if ($rootPackage['name'] === $package) {
                continue;
            }
            if (isset($values['pretty_version'])) {
                $category = $values['category'] ?? '';
                $constraint = $values['constraint'] ?? '';
                $prefix = $values['prefix'] ?? $this->prefix . ' uses';
                $entries[] = sprintf('%s%s %s: <info>%s</info>', $prefix, $category, "$package $constraint", $values['pretty_version']);
            } // otherwise, it's a virtual package
        }

        return implode(PHP_EOL, $entries);
    }
}
