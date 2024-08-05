<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest;

use KevinGH\Box\StubGenerator as BaseStubGenerator;

use InvalidArgumentException;
use function file_exists;
use function file_get_contents;
use function implode;
use function is_readable;
use function ltrim;
use function preg_match;
use function sprintf;
use function str_replace;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
class StubGenerator
{
    private string $stubTemplate;

    /**
     * @param string[] $resources
     */
    public function __construct(
        string $templatePath,
        array $resources,
        string $version
    ) {
        if (!file_exists($templatePath) || !is_readable($templatePath)) {
            throw new InvalidArgumentException(sprintf('Filename "%s" does not exists or is not readable.', $templatePath));
        }

        $template = file_get_contents($templatePath);
        // @phpstan-ignore argument.type
        $template = ltrim($template, "<?php\n");

        $this->stubTemplate = str_replace(
            ['%manifest_files%', '%version%'],
            [implode("', '", $resources), $version],
            $template
        );
    }

    /**
     * @param null|non-empty-string $shebang The shebang line
     */
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
