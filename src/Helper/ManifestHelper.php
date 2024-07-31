<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Helper;

use Bartlett\BoxManifest\StubGenerator;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

use Phar;
use function dirname;
use function file_exists;
use function file_get_contents;
use function implode;
use function realpath;
use function str_starts_with;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class ManifestHelper extends Helper
{
    public const NAME = 'manifest';

    public function configureOption(InputDefinition $definition): void
    {
        if (Phar::running()) {
            $definition->addOption(
                new InputOption(
                    'manifest',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'Show software components bundled (either from : ' .
                    implode(', ', ManifestFile::values()) .
                    ' or phar metadata field)'
                )
            );
        }
    }

    /**
     * Search for file binary resources either on local installation or into the PHAR distribution
     * to retrieve the manifest contents.
     * When not found, fallback to `metadata` field in case of PHP Archive.
     *
     * @param string[] $resources
     */
    public function get(array $resources): ?string
    {
        if (Phar::running()) {
            $phar = new Phar($_SERVER['argv'][0]);
        }

        foreach ($resources as $resource) {
            if (Phar::running()) {
                $resolved = isset($phar[$resource]) ? $phar[$resource]->getPathname() : false;
            } else {
                $resolved = realpath($resource) ?: (file_exists($resource) ? $resource : null);
            }
            if ($resolved) {
                return file_get_contents($resolved) ?: null;
            }
        }

        if (Phar::running()) {
            return $phar->getMetadata();    // @phpstan-ignore-line
        }

        return null;
    }

    /**
     * @param string|null $templatePath
     * @param string[]|null $resources
     * @param string[][]|null $mapFiles
     */
    public function getStubGenerator(string $templatePath = null, array $resources = null, array $mapFiles = null): StubGenerator
    {
        if (null === $templatePath) {
            $templatePath = dirname(__DIR__, 2) . '/resources/default_stub.template';
        }
        if (!empty($mapFiles) && empty($resources)) {
            $manifestDir = '.box.manifests/';
            $resources = [];
            foreach ($mapFiles as $mapFile) {
                foreach ($mapFile as $target) {
                    if (str_starts_with($target, $manifestDir)) {
                        $resources[] = $target;
                    }
                }
            }
        }
        if (empty($resources)) {
            $resources = ManifestFile::values();
        }
        return new StubGenerator($templatePath, $resources);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
