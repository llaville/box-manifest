<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Laurent Laville
 * @since Release 3.2.0
 */
final class ConsoleTextManifestBuilder implements ManifestBuilderInterface
{
    /**
     * @inheritDoc
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
        $entries[] = [$this->getPackageLink($rootPackage['name']), '', $version, ''];

        $allRequirements = [
            'Direct (for production)' => $composerJson['require'],
            'Direct (for development)' => $composerJson['require-dev'] ?? [],
        ];

        foreach ($allRequirements as $category => $requirements) {
            foreach ($requirements as $req => $constraint) {
                if (!empty($constraint)) {
                    $constraint = sprintf('<comment>requires %s</comment>', $constraint);
                } else {
                    $constraint = '<comment>uses</comment>';
                }
                if ('php' === $req) {
                    $entries[] = [$req, $constraint, '', $category];
                } elseif (str_starts_with($req, 'ext-')) {
                    $entries[] = [$req, $constraint, '', $category];
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
                $category = $values['category'] ?? 'Transitive';
                $constraint = $values['constraint'] ?? '';
                $entries[] = [$this->getPackageLink($package), $constraint, $values['pretty_version'], $category];
            } // otherwise, it's a virtual package
        }

        $output = new BufferedOutput();
        $output->setDecorated(true);
        $output->writeln('');

        $headers = ['Package', 'Constraint', 'Version', "Dependency's category"];

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($entries)
        ;
        $table->render();

        return $output->fetch();
    }

    private function getPackageLink(string $name): string
    {
        return sprintf('<href=https://packagist.org/packages/%s>%s</>', $name, $name);
    }
}
