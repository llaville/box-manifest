<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer\Manifest;

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

use CycloneDX\Core\Enums\ComponentType;
use CycloneDX\Core\Factories\LicenseFactory;
use CycloneDX\Core\Models\Bom;
use CycloneDX\Core\Models\Component;
use CycloneDX\Core\Models\Property;
use CycloneDX\Core\Models\Tool;
use CycloneDX\Core\Serialization\DOM\NormalizerFactory as DOMNormalizerFactory;
use CycloneDX\Core\Serialization\JSON\NormalizerFactory;
use CycloneDX\Core\Serialization\JsonSerializer;
use CycloneDX\Core\Serialization\Serializer;
use CycloneDX\Core\Serialization\XmlSerializer;
use CycloneDX\Core\Utils\BomUtility;
use PackageUrl\PackageUrl;

use DateTime;
use DomainException;
use Exception;
use function explode;
use function get_debug_type;
use function sprintf;
use function substr;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class SbomManifestBuilder implements ManifestBuilderInterface
{
    protected Serializer $serializer;
    protected string $boxVersion;
    protected string $boxManifestVersion;

    protected string $specVersion;
    protected string $bomFormat = 'CycloneDX';

    protected Bom $bom;

    public function __construct(object $normalizer, string $boxVersion, string $boxManifestVersion, bool $isImmutable)
    {
        if ($normalizer instanceof DOMNormalizerFactory) {
            $serializer = new XmlSerializer($normalizer);
        } elseif ($normalizer instanceof NormalizerFactory) {
            $serializer = new JsonSerializer($normalizer);
        } else {
            throw new DomainException(sprintf('Normalizer "%s" is not supported.', get_debug_type($normalizer)));
        }

        $this->specVersion = $normalizer->getSpec()->getVersion()->value;
        $this->serializer = $serializer;
        $this->boxVersion = $boxVersion;
        $this->boxManifestVersion = $boxManifestVersion;

        $this->bom = new Bom();
        if (!$isImmutable) {
            try {
                $this->bom->setSerialNumber(BomUtility::randomSerialNumber());
            } catch (Exception) {
            }
            $this->bom->getMetadata()->setTimestamp(new DateTime());

            $boxTool = new Tool();
            $boxTool->setVendor('box-project');
            $boxTool->setName('box');
            $boxTool->setVersion($this->boxVersion);

            $boxManifestTool = new Tool();
            $boxManifestTool->setVendor('bartlett');
            $boxManifestTool->setName('box-manifest');
            $boxManifestTool->setVersion($this->boxManifestVersion);

            $this->bom->getMetadata()->getTools()->addItems($boxTool, $boxManifestTool);
        }
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


        [$group, $name] = explode('/', $rootPackage['name']);

        $type = $rootPackage['type'] === 'library' ? ComponentType::Library : ComponentType::Application;

        // publisher
        $component = new Component($type, $name);
        $component->setVersion($version);
        $component->setGroup($group);
        $component->setDescription($composerJson['description']);

        $purl = new PackageUrl('composer', $rootPackage['name']);
        $purl->setVersion($version);
        $component->setPackageUrl($purl);
        $component->setBomRefValue((string) $purl);

        // scope
        if (isset($composerJson['license'])) {
            $licenseFactory = new LicenseFactory();

            if (!empty($composerJson['license'])) {
                $component->getLicenses()->addItems(
                    $licenseFactory->makeFromString($composerJson['license'])
                );
            }
        }

        // metadata
        $this->bom->getMetadata()->getProperties()->addItems(
            new Property('specVersion', $this->specVersion),
            new Property('bomFormat', $this->bomFormat),
        );

        // components
        $componentRepository = $this->bom->getComponents();

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

            $type = $values['type'] === 'library' ? ComponentType::Library : ComponentType::Application;

            $component = new Component($type, $name);
            $component->setVersion($version);
            $component->setGroup($group);

            $purl = new PackageUrl('composer', $package);
            $purl->setVersion($version);
            $component->setPackageUrl($purl);
            $component->setBomRefValue((string) $purl);

            $componentRepository->addItems($component);
        }

        return $this->serializer->serialize($this->bom, true);
    }
}
