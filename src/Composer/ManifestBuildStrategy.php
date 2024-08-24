<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

/**
 * Contract to implement on each new strategy.
 *
 * @see https://refactoring.guru/design-patterns/strategy to learn more about Design Pattern Strategy
 *
 * @author Laurent Laville
 * @since Release 3.5.0
 */
interface ManifestBuildStrategy
{
    public const MIME_TYPE_SBOM_JSON = 'application/vnd.sbom+json';
    public const MIME_TYPE_SBOM_XML = 'application/vnd.sbom+xml';
    public const MIME_TYPE_TEXT_PLAIN = 'text/plain';
    public const MIME_TYPE_OCTET_STREAM = 'application/octet-stream';

    public function getMimeType(string $resourceFile, ?string $version): string;

    public function getCallable(string $outputFormat, ?string $resourceFile): callable;
}
