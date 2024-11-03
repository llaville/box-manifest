<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

/**
 * @author Laurent Laville
 */
interface ManifestBuilderInterface
{
    /**
     * @param array{
     *     "composer.json": array{
     *         description: string,
     *         require: array<string, string>,
     *         require-dev: array<string, string>
     *     },
     *     "composer.lock": array<string, mixed>,
     *     "installed.php": array{
     *         'root': array{
     *              name: string,
     *              pretty_version: string,
     *              version: string,
     *              reference: string,
     *              type: string,
     *              aliases: array<int, string>
     *         },
     *         'versions': array<string, array{constraint?: string, category?: string}>
     *     }
     * } $content
     */
    public function __invoke(array $content): string;
}
