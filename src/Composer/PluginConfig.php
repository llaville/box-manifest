<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use function realpath;
use function rtrim;
use const DIRECTORY_SEPARATOR;

/**
 * Configuration for the Composer Plugin.
 *
 * @author Laurent Laville
 * @since Release 3.6.0
 */
final class PluginConfig
{
    /**
     * @param array<string, mixed> $extra
     */
    public function __construct(private array $extra)
    {
    }

    public function getConfigFile(string $baseDir = null, string $default = null): ?string
    {
        $filename = $this->extra['config-file'] ?? $default ?? null;

        if ($baseDir) {
            $filename = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        }

        $absolutePath = realpath($filename);

        return ($absolutePath ? : null);
    }

    /**
     * @return array<string, string>
     */
    public function getMap(): array
    {
        return $this->extra['map'] ?? [];
    }
}
