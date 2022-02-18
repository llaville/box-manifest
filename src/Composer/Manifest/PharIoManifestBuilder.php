<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use function count;
use function in_array;

/**
 * @author Laurent Laville
 */
final class PharIoManifestBuilder extends SimpleXmlManifestBuilder
{
    public const XMLNS = 'https://phar.io/xml/manifest/1.0';

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    protected function normalize(array $data, array $context): array
    {
        if (!in_array($data['contains']['@type'], ['application', 'extension', 'library'])) {
            $data['contains']['@type'] = 'library';
        }

        if (count($data['copyright']['license']) > 1) {
            $data['copyright']['license'] = [$data['copyright']['license'][0]];
        }

        if (!isset($data['copyright']['license'][0]['@url'])) {
            $data['copyright']['license'][0]['@url'] = '';
        }

        foreach ($data['bundles']['component'] ?? [] as $index => $component) {
            foreach ($component as $attribute => $value) {
                if (in_array($attribute, $context[AbstractNormalizer::IGNORED_ATTRIBUTES])) {
                    unset($data['bundles']['component'][$index][$attribute]);
                }
            }
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    protected function serialize(array $data, array $context): string
    {
        $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = ['@constraint'];
        $data = $this->normalize($data, $context);
        return parent::serialize($data, $context);
    }
}
