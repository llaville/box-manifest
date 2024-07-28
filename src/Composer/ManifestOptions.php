<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Helper\ManifestFormat;

use Fidry\Console\Input\IO;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * @author Laurent Laville
 * @since Release 3.5.0
 */
final class ManifestOptions
{
    private const BOOTSTRAP_OPTION = 'bootstrap';
    private const FORMAT_OPTION = 'format';
    private const SBOM_SPEC_OPTION = 'sbom-spec';
    private const OUTPUT_OPTION = 'output-file';

    public function __construct(private StyleInterface $io)
    {
    }

    public function getBootstrap(): ?string
    {
        return $this->io->getOption(self::BOOTSTRAP_OPTION)->asNullableString();
    }

    public function getFormat(bool $raw = false): string|null|ManifestFormat
    {
        $rawFormat = $this->io->getOption(self::FORMAT_OPTION)->asString();

        if ($raw) {
            return $rawFormat;
        }
        return ManifestFormat::tryFrom($rawFormat);
    }

    public function getFormatDisplay(): string
    {
        return match ($this->getFormat()) {
            ManifestFormat::auto => 'AUTO detection mode',
            ManifestFormat::plain, ManifestFormat::ansi, ManifestFormat::console => 'TEXT',
            ManifestFormat::sbomXml, ManifestFormat::sbomJson => 'SBom ' . $this->getSbomSpec(),
            default => $this->getFormat(true),
        };
    }

    public function getSbomSpec(): string
    {
        return $this->io->getOption(self::SBOM_SPEC_OPTION)->asString();
    }

    public function getOutputFile(): ?string
    {
        return $this->io->getOption(self::OUTPUT_OPTION)->asNullableString();
    }
}
