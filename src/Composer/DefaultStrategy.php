<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use InvalidArgumentException;
use function class_exists;
use function in_array;
use function sprintf;
use function str_ends_with;

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

    public function getMimeType(string $resourceFile, ?string $version): string
    {
        $recognizedFilePatternsRules = [
            'sbom.json' => self::MIME_TYPE_SBOM_JSON,
            'sbom.xml' => self::MIME_TYPE_SBOM_XML,
            '.cdx.json' => self::MIME_TYPE_SBOM_JSON,
            '.cdx.xml' => self::MIME_TYPE_SBOM_XML,
            'manifest.txt' => self::MIME_TYPE_TEXT_PLAIN,
            'plain.txt' => self::MIME_TYPE_TEXT_PLAIN,
        ];

        foreach ($recognizedFilePatternsRules as $extension => $mimeType) {
            if (str_ends_with($resourceFile, $extension)) {
                if (in_array($mimeType, [self::MIME_TYPE_SBOM_JSON, self::MIME_TYPE_SBOM_XML]) && !empty($version)) {
                    $mimeType .= '; version=' . $version;
                }
                return $mimeType;
            }
        }

        return self::MIME_TYPE_OCTET_STREAM;
    }

    public function getCallable(string $outputFormat, ?string $resourceFile): callable
    {
        if ('auto' == $outputFormat) {
            if (null === $resourceFile) {
                return [$this->factory, 'toConsole'];
            }

            $recognizedFilePatternsRules = [
                'sbom.json' => [$this->factory, 'toSbomJson'],
                'sbom.xml' => [$this->factory, 'toSbomXml'],
                '.cdx.json' => [$this->factory, 'toSbomJson'],
                '.cdx.xml' => [$this->factory, 'toSbomXml'],
                'manifest.txt' => [$this->factory, 'toText'],
                'plain.txt' => [$this->factory, 'toText'],
                'console-style.txt' => [$this->factory, 'toHighlight'],
                'console-table.txt' => [$this->factory, 'toConsole'],
            ];

            foreach ($recognizedFilePatternsRules as $extension => $callable) {
                if (str_ends_with($resourceFile, $extension)) {
                    return $callable;
                }
            }

            throw new InvalidArgumentException(sprintf('Cannot auto-detect format for "%s" resource file', $resourceFile));
        }

        $recognizedOutputFormatRules = [
            'console-table' => [$this->factory, 'toConsole'],
            'console-style' => [$this->factory, 'toHighlight'],
            'plain' => [$this->factory, 'toText'],
            'sbom-json' => [$this->factory, 'toSbomJson'],
            'sbom-xml' => [$this->factory, 'toSbomXml'],
        ];

        foreach ($recognizedOutputFormatRules as $format => $callable) {
            if ($outputFormat === $format) {
                return $callable;
            }
        }

        if (!class_exists($outputFormat)) {
            throw new InvalidArgumentException(sprintf('Format "%s" is not supported', $outputFormat));
        }

        return [$this->factory, 'fromClass'];
    }
}
