<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest;

use KevinGH\Box\StubGenerator as BaseStubGenerator;

use function implode;
use function preg_match;
use function str_replace;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class StubGenerator
{
    private string $stubTemplate;

    private const MANIFEST_STUB_TEMPLATE = <<<'STUB'
        if ($argc > 1 && $argv[1] === '--manifest') {
            $resources = ($argc > 2 && !str_starts_with($argv[2], '-')) ? [$argv[2]] : [%manifest_files%];

            foreach ($resources as $resource) {
                $filename = "phar://%alias%/{$resource}";
                if (file_exists($filename)) {
                    echo file_get_contents($filename), PHP_EOL;
                    $status = 0;
                    break;
                } elseif (count($resources) === 1) {
                    echo sprintf('Manifest "%s" is not available in this PHP Archive.', $resource), PHP_EOL;
                    $status = 1;
                    break;
                }
            }
            exit($status);
        }
        STUB;

    /**
     * @param string|null $template
     * @param string[]|null $resources
     */
    public function __construct(
        string $template = null,
        array $resources = null
    ) {
        if (null === $template) {
            $template = self::MANIFEST_STUB_TEMPLATE;
        }
        if (null === $resources) {
            $resources = ['manifest.txt', 'manifest.xml', 'sbom.xml', 'sbom.json'];
        }

        $this->stubTemplate = str_replace(
            '%manifest_files%',
            "'" . implode("', '", $resources) . "'",
            $template
        );
    }
    public function generateStub(
        ?string $alias = null,
        ?string $banner = null,
        ?string $index = null,
        bool $intercept = false,
        ?string $shebang = null,
        bool $checkRequirements = true
    ): string {
        if (null === $alias) {
            $this->stubTemplate = str_replace('%alias%', '" . __FILE__ . "', $this->stubTemplate);
        } else {
            $this->stubTemplate = str_replace('%alias%', $alias, $this->stubTemplate);
        }

        $stub = BaseStubGenerator::generateStub($alias, $banner, $index, $intercept, $shebang, $checkRequirements);

        $matched = preg_match('/(.*)(Phar::mapPhar.*?;\n)(.*)/smi', $stub, $matches);

        if ($matched) {
            return $matches[1] . $matches[2] . "\n{$this->stubTemplate}\n" . $matches[3];
        }
        return str_replace('<?php', "<?php\n\n{$this->stubTemplate}\n", $stub);
    }
}
