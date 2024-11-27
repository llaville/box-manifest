<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest;

use InvalidArgumentException;
use function addcslashes;
use function file_exists;
use function file_get_contents;
use function implode;
use function is_readable;
use function sprintf;
use function str_replace;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class StubGenerator
{
    private string $manifestTemplate;

    private const CHECK_FILE_NAME = 'bin/check-requirements.php';

    private const MANIFEST_TEMPLATE = <<<'MANIFEST'
        $withManifest = array_search('--manifest', $argv);
        $withoutAnsi = in_array('--no-ansi', $argv);

        if ($withManifest !== false) {
            $manifestDir = '%manifest_dir%';
            $resources = (($argc - 1 > $withManifest) && !str_starts_with($argv[$withManifest + 1], '-')) ? [$argv[$withManifest + 1]] : ['%manifest_files%'];

            foreach ($resources as $resource) {
                $res = str_replace($manifestDir, '', $resource);
                $filename = "phar://%alias%/$manifestDir$res";
                if (file_exists($filename)) {
                    $manifest = file_get_contents($filename);
                    if ($withoutAnsi !== false) {
                        $manifest = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $manifest);
                    }
                    echo $manifest, PHP_EOL;
                    exit(0);
                } elseif (count($resources) === 1) {
                    echo sprintf('Manifest "%s" is not available in this PHP Archive.', $resource), PHP_EOL;
                    exit(2);
                }
            }
            echo 'No manifest found in this PHP Archive', PHP_EOL;
            exit(1);
        }
        MANIFEST;

    private const STUB_TEMPLATE = <<<'STUB'
        __BOX_SHEBANG__
        <?php
        __BOX_BANNER__

        __BOX_PHAR_MANIFEST__

        __BOX_PHAR_CONFIG__

        __HALT_COMPILER(); ?>

        STUB;

    /**
     * @param string[] $resources
     */
    public function __construct(
        ?string $templatePath,
        private readonly array $resources,
        private readonly string $version,
        private readonly string $resourceDir
    ) {
        if (null === $templatePath) {
            $this->manifestTemplate = self::MANIFEST_TEMPLATE;
        } else {
            if (!file_exists($templatePath) || !is_readable($templatePath)) {
                throw new InvalidArgumentException(
                    sprintf('Filename "%s" does not exists or is not readable.', $templatePath)
                );
            }
            $this->manifestTemplate = file_get_contents($templatePath) ?: '';
        }
    }

    public function generateStub(
        string $alias,
        string $banner,
        ?string $index,
        string $shebang,
        bool $intercept,
        bool $checkRequirements
    ): string {
        $stub = self::STUB_TEMPLATE;

        // 1. @link https://box-project.github.io/box/configuration/#shebang-shebang
        $stub = str_replace(
            "__BOX_SHEBANG__",
            $shebang,
            $stub,
        );

        // 2. @link https://box-project.github.io/box/configuration/#banner-banner
        $stub = str_replace(
            "__BOX_BANNER__\n",
            $this->generateBannerStmt($banner),
            $stub,
        );

        // 3. Manifest(s)
        $stub = str_replace(
            "__BOX_PHAR_MANIFEST__\n",
            $this->generatePharManifestStmt(
                $alias,
                $this->resourceDir,
                $this->resources,
                $this->version
            ),
            $stub
        );

        // 4. @link https://box-project.github.io/box/configuration/#intercept-intercept
        //    @link https://box-project.github.io/box/configuration/#main-main
        return str_replace(
            "__BOX_PHAR_CONFIG__\n",
            $this->generatePharConfigStmt(
                $alias,
                $index,
                $intercept,
                $checkRequirements,
            ),
            $stub,
        );
    }

    private function generateBannerStmt(string $banner): string
    {
        if (empty($banner)) {
            return '';
        }

        $generatedBanner = "/**\n * ";

        $generatedBanner .= str_replace(
            " \n",
            "\n",
            str_replace("\n", "\n * ", $banner),
        );

        $generatedBanner .= "\n */";

        return "\n" . $generatedBanner . "\n";
    }

    /**
     * @param string[] $resources
     */
    private function generatePharManifestStmt(string $alias, string $resourceDir, array $resources, string $version): string
    {
        return str_replace(
            ['%alias%', '%manifest_dir%', '%manifest_files%', '%version%'],
            [$alias, $resourceDir, implode("', '", $resources), $version],
            $this->manifestTemplate
        );
    }

    private function generatePharConfigStmt(
        string $alias,
        ?string $index = null,
        bool $intercept = false,
        bool $checkRequirements = true,
    ): string {
        $stub = [];

        $stub[] = '';
        $quote = "'";
        $aliasStmt = 'Phar::mapPhar(' . $quote . addcslashes($alias, $quote) . $quote . ');';
        $stub[] = $aliasStmt;

        if ($intercept) {
            $stub[] = 'Phar::interceptFileFuncs();';
        }

        if ($checkRequirements) {
            $stub[] = '';
            $checkRequirementsFile = self::CHECK_FILE_NAME;
            $stub[] = "require 'phar://{$alias}/.box/{$checkRequirementsFile}';";
        }

        if (null !== $index) {
            $stub[] = '';
            $indexPath = "'phar://{$alias}/{$index}';";
            $stub[] = "require \$_SERVER['SCRIPT_FILENAME'] = {$indexPath}";
        }

        return implode("\n", $stub) . "\n";
    }
}
