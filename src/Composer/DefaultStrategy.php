<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Helper\ManifestFile;
use Bartlett\BoxManifest\Helper\ManifestFormat;

use InvalidArgumentException;
use function class_exists;
use function sprintf;

/**
 * This is the default strategy used to build your manifest(s).
 *
 * @author Laurent Laville
 * @since Release 3.5.0
 */
final readonly class DefaultStrategy implements ManifestBuildStrategy
{
    public function __construct(private ManifestFactory $factory)
    {
    }

    public function build(ManifestOptions $options): ?string
    {
        $factory = $this->factory;

        /** @var string $rawFormat */
        $rawFormat = $options->getFormat(true);
        $format = $options->getFormat();
        $outputFile = $options->getOutputFile();
        $sbomSpec = $options->getSbomSpec();

        $output = $outputFile ? ManifestFile::tryFrom(basename($outputFile)) : null;

        return match ($format) {
            ManifestFormat::auto => match ($output) {
                null, ManifestFile::ansi => $factory->toHighlight(),
                ManifestFile::consoleTable => $factory->toConsole(),
                ManifestFile::txt => $factory->toText(),
                ManifestFile::sbomXml => $factory->toSbom('xml', $sbomSpec),
                ManifestFile::sbomJson => $factory->toSbom('json', $sbomSpec),
                default => match (pathinfo($outputFile ? : '', PATHINFO_EXTENSION)) {
                    'xml' => $factory->toSbom('xml', $sbomSpec),
                    'json' => $factory->toSbom('json', $sbomSpec),
                    '', 'txt' => $factory->toText(),
                    default => throw new InvalidArgumentException('Cannot auto-detect format with such output file')
                }
            },
            ManifestFormat::plain => $factory->toText(),
            ManifestFormat::ansi => $factory->toHighlight(),
            ManifestFormat::console => $factory->toConsole(),
            ManifestFormat::sbomXml => $factory->toSbom('xml', $sbomSpec),
            ManifestFormat::sbomJson => $factory->toSbom('json', $sbomSpec),
            default => class_exists($rawFormat)
                ? $factory->fromClass($rawFormat)
                : throw new InvalidArgumentException(sprintf('Format "%s" is not supported', $rawFormat))
        };
    }
}
