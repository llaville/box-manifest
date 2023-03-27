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
class ConsoleTextManifestBuilder implements ManifestBuilderInterface
{
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
        $entries[] = [$rootPackage['name'], $version];

        $allRequirements = [
            '' => $composerJson['require'],
            ' (for development)' => $composerJson['require-dev'] ?? [],
        ];

        foreach ($allRequirements as $category => $requirements) {
            foreach ($requirements as $req => $constraint) {
                if (!empty($constraint)) {
                    $constraint = sprintf('<comment>%s</comment>', $constraint);
                    $prefix = '<comment>requires</comment>';
                } else {
                    $prefix = '<comment>uses</comment>';
                }
                $installedPhp['versions'][$req]['prefix'] = $prefix;
                if ('php' === $req) {
                    $entries[] = [sprintf('%s%s %s', $prefix, $category, "$req $constraint"), phpversion()];
                } elseif (str_starts_with($req, 'ext-')) {
                    $extension = substr($req, 4);
                    $entries[] = [sprintf('%s%s %s', $prefix, $category, "$req $constraint"), phpversion($extension)];
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
                $prefix = $values['prefix'] ?? '<comment>uses</comment>';
                $entries[] = [sprintf('%s%s %s', $prefix, $category, "$package $constraint"), $values['pretty_version']];
            } // otherwise, it's a virtual package
        }

        $output = new BufferedOutput();
        $output->setDecorated(true);
        $output->writeln('');

        $headers = ['Package', 'Version'];

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($entries)
        ;
        $table->render();

        return $output->fetch();
    }
}
