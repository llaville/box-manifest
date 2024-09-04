<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Helper\ManifestFormat;

use Fidry\Console\IO;

use RuntimeException;
use function file_exists;
use function is_dir;
use function sprintf;

/**
 * @author Laurent Laville
 * @since Release 3.5.0
 */
final class ManifestOptions
{
    public const STAGES_OPTION =  'stages';
    public const BOOTSTRAP_OPTION = 'bootstrap';
    public const FORMAT_OPTION = 'output-format';
    public const SBOM_SPEC_OPTION = 'sbom-spec';
    public const OUTPUT_OPTION = 'output-file';
    public const OUTPUT_STUB_OPTION = 'output-stub';
    public const OUTPUT_CONF_OPTION = 'output-conf';
    public const TEMPLATE_OPTION = 'template';
    public const RESOURCE_OPTION = 'resource';
    public const RESOURCE_DIR_OPTION = 'resource-dir';
    public const IMMUTABLE_OPTION = 'immutable';
    public const WORKING_DIR_OPTION = 'working-dir';

    public function __construct(private readonly IO $io)
    {
    }

    public function getBootstrap(): ?string
    {
        return $this->io->getTypedOption(self::BOOTSTRAP_OPTION)->asNullableString();
    }

    public function getFormat(bool $raw = false): string|null|ManifestFormat
    {
        $rawFormat = $this->io->getTypedOption(self::FORMAT_OPTION)->asString();

        if ($raw) {
            return $rawFormat;
        }
        return ManifestFormat::tryFrom($rawFormat);
    }

    public function getSbomSpec(): string
    {
        return $this->io->getTypedOption(self::SBOM_SPEC_OPTION)->asString();
    }

    /**
     * @return string[]
     */
    public function getResources(): array
    {
        return $this->io->getTypedOption(self::RESOURCE_OPTION)->asStringList();
    }

    public function getOutputFile(): ?string
    {
        return $this->io->getTypedOption(self::OUTPUT_OPTION)->asNullableString();
    }

    public function getOutputStubFile(): ?string
    {
        return $this->io->getTypedOption(self::OUTPUT_STUB_OPTION)->asNullableString();
    }

    public function getOutputConfFile(): ?string
    {
        return $this->io->getTypedOption(self::OUTPUT_CONF_OPTION)->asNullableString();
    }

    public function getTemplateFile(): ?string
    {
        return $this->io->getTypedOption(ManifestOptions::TEMPLATE_OPTION)->asNullableString();
    }

    public function getResourceDir(): string
    {
        return $this->io->getTypedOption(self::RESOURCE_DIR_OPTION)->asString();
    }

    public function getWorkingDir(): ?string
    {
        $workingDir = $this->io->getTypedOption(ManifestOptions::WORKING_DIR_OPTION)->asNullableNonEmptyString();

        if ($workingDir === null) {
            return null;
        }

        if (!file_exists($workingDir) || !is_dir($workingDir)) {
            throw new RuntimeException(
                sprintf('Invalid working directory specified, "%s" does not exist or is not a directory.', $workingDir)
            );
        }

        return $workingDir;
    }

    public function isImmutable(): bool
    {
        return $this->io->getTypedOption(self::IMMUTABLE_OPTION)->asBoolean();
    }
}
