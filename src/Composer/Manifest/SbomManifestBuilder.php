<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

use CycloneDX\Core\Enums\Classification;
use CycloneDX\Core\Models\Bom;
use CycloneDX\Core\Models\Component;
use CycloneDX\Core\Models\License\DisjunctiveLicenseWithId;
use CycloneDX\Core\Models\MetaData;
use CycloneDX\Core\Models\Tool;
use CycloneDX\Core\Repositories\DisjunctiveLicenseRepository;
use CycloneDX\Core\Repositories\ToolRepository;
use CycloneDX\Core\Serialize\SerializerInterface;
use CycloneDX\Core\Spdx\License as LicenseValidator;
use PackageUrl\PackageUrl;

use function explode;
use function sprintf;
use function substr;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class SbomManifestBuilder implements ManifestBuilderInterface
{
    protected SerializerInterface $serializer;
    protected string $boxVersion;

    public function __construct(SerializerInterface $serializer, string $boxVersion)
    {
        $this->serializer = $serializer;
        $this->boxVersion = $boxVersion;
    }

    public function __invoke(array $content): string
    {
        $composerJson = $content['composer.json'];
        $installedPhp = $content['installed.php'];
        $rootPackage = $installedPhp['root'];

        if (!empty($rootPackage['aliases'])) {
            $version = sprintf(
                '%s@%s',
                $rootPackage['aliases'][0],
                substr($rootPackage['reference'], 0, 7)
            );
        } elseif (isset($rootPackage['pretty_version'])) {
            $version = sprintf(
                '%s@%s',
                $rootPackage['pretty_version'],
                substr($rootPackage['reference'], 0, 7)
            );
        } else {
            $version = $rootPackage['version'];
        }

        $bom = new Bom();

        [$group, $name] = explode('/', $rootPackage['name']);

        $type = $rootPackage['type'] === 'library' ? Classification::LIBRARY : Classification::APPLICATION;

        // publisher
        $component = new Component($type, $name, $version);
        $component->setGroup($group);
        $component->setDescription($composerJson['description']);

        $purl = new PackageUrl('composer', $rootPackage['name']);
        $purl->setVersion($version);
        $component->setPackageUrl($purl);
        $component->setBomRefValue((string) $purl);

        // scope
        if (isset($composerJson['license'])) {
            $licenseValidator = new LicenseValidator();
            $license = DisjunctiveLicenseWithId::makeValidated($composerJson['license'], $licenseValidator);
            $licenses = new DisjunctiveLicenseRepository($license);
            $component->setLicense($licenses);
        }

        // metadata
        $metadata = new MetaData();
        $metadata->setComponent($component);

        $boxTool = new Tool();
        $boxTool->setVendor('box-project');
        $boxTool->setName('box');
        $boxTool->setVersion($this->boxVersion);

        $metadata->setTools(new ToolRepository($boxTool));

        $bom->setMetaData($metadata);

        // components
        $componentRepository = $bom->getComponentRepository();

        foreach ($installedPhp['versions'] as $package => $values) {
            if ($package === $rootPackage['name']) {
                // does not include root package
                continue;
            }
            if (!isset($values['pretty_version'])) {
                // it's a virtual package
                continue;
            }
            if (empty($values['aliases'])) {
                $version = $values['pretty_version'];
            } else {
                $version = sprintf(
                    '%s@%s',
                    $values['pretty_version'],
                    substr($values['reference'], 0, 7)
                );
            }

            [$group, $name] = explode('/', $package);

            $type = $values['type'] === 'library' ? Classification::LIBRARY : Classification::APPLICATION;

            $component = new Component($type, $name, $version);
            $component->setGroup($group);

            $purl = new PackageUrl('composer', $package);
            $purl->setVersion($version);
            $component->setPackageUrl($purl);
            $component->setBomRefValue((string) $purl);

            $componentRepository->addComponent($component);
        }

        return $this->serializer->serialize($bom);
    }
}
