<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

use function array_keys;
use function substr;

/**
 * @author Laurent Laville
 */
final class SimpleXmlManifestBuilder implements ManifestBuilderInterface
{
    public const XMLNS = 'https://phar.io/xml/manifest/1.1';

    /**
     * {@inheritDoc}
     */
    public function __invoke(array $content): string
    {
        $composerJson = $content['composer.json'];
        $composerLock = $content['composer.lock'];
        $installedPhp = $content['installed.php'];

        // copyright section
        $authorCollection = [];
        $authors = $composerJson['authors'] ?? [];
        foreach ($authors as $author) {
            $parts = ['@name' => $author['name']];
            if (isset($author['email'])) {
                $parts['@email'] = $author['email'];
            }
            $authorCollection[] = $parts;
        }
        $licenseCollection = [];
        $licenses = (array) ($composerJson['license'] ?? []);
        foreach ($licenses as $license) {
            $licenseCollection[] = ['@type' => $license];
        }

        // requires section
        $platform = $composerLock['platform'] ?? [];
        if (!isset($platform['php'])) {
            $platform['php'] = '*';
        }
        $extensions = [];
        foreach (array_keys($platform) as $key) {
            if (substr($key, 0, 4) === 'ext-') {
                $extensions[] = ['@name' => substr($key, 4)];
            }
        }

        // bundles section
        $packages = $installedPhp['versions'] ?? [];
        $bundles = [];
        foreach ($packages as $name => $values) {
            $bundles[] = [
                '@name' => $name,
                '@version' => $values['version'] ?? '',
                '@constraint' => $composerJson['require'][$name] ?? '',
            ];
        }

        $data = ['@xmlns' => self::XMLNS];
        if (!empty($authorCollection)) {
            $data['copyright']['author'] = $authorCollection;
        }
        if (!empty($licenseCollection)) {
            $data['copyright']['license'] = $licenseCollection;
        }
        $data['requires']['php']['@version'] = $platform['php'];
        if (!empty($extensions)) {
            $data['requires']['php']['ext'] = $extensions;
        }
        if (!empty($bundles)) {
            $data['bundles']['component'] = $bundles;
        }

        return $this->serialize($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function serialize(array $data): string
    {
        $context = [
            XmlEncoder::FORMAT_OUTPUT => true,
            XmlEncoder::ENCODING => 'utf-8',
            XmlEncoder::ROOT_NODE_NAME => 'phar',
        ];
        $encoder = new XmlEncoder();
        return $encoder->encode($data, 'xml', $context);
    }
}
