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

    public function getFormatDisplay(): string
    {
        // @phpstan-ignore return.type
        return match ($this->getFormat()) {
            ManifestFormat::auto => 'AUTO detection mode',
            ManifestFormat::plain, ManifestFormat::ansi, ManifestFormat::console => 'TEXT',
            ManifestFormat::sbomXml, ManifestFormat::sbomJson => 'SBOM ' . $this->getSbomSpec(),
            default => $this->getFormat(true),
        };
    }

    public function getSbomSpec(): string
    {
        return $this->io->getTypedOption(self::SBOM_SPEC_OPTION)->asString();
    }

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
}
