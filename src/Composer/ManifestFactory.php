<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Bartlett\BoxManifest\Composer\Manifest\ConsoleTextManifestBuilder;
use Bartlett\BoxManifest\Composer\Manifest\DecorateTextManifestBuilder;
use Bartlett\BoxManifest\Composer\Manifest\SbomManifestBuilder;
use Bartlett\BoxManifest\Composer\Manifest\SimpleTextManifestBuilder;

use CycloneDX\Core\Serialization\DOM\NormalizerFactory as DomNormalizerFactory;
use CycloneDX\Core\Serialization\JSON\NormalizerFactory as JsonNormalizerFactory;
use CycloneDX\Core\Spec\SpecFactory;
use CycloneDX\Core\Spec\Version;

use KevinGH\Box\Configuration\Configuration;

use DomainException;
use ValueError;
use function array_column;
use function array_key_exists;
use function class_exists;
use function file_exists;
use function implode;
use function is_readable;
use function is_string;
use function preg_replace;
use function sprintf;
use const DIRECTORY_SEPARATOR;

/**
 * @author Laurent Laville
 */
final class ManifestFactory
{
    private ManifestBuildStrategy $strategy;

    public function __construct(
        private readonly Configuration $config,
        private readonly bool $isDecorated,
        private readonly string $boxVersion,
        private readonly string $boxManifestVersion,
        private readonly bool $immutableCopy
    ) {
        $this->setStrategy(new DefaultStrategy($this));
    }

    public function setStrategy(ManifestBuildStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function build(ManifestOptions $options): ?string
    {
        /** @var string $rawFormat */
        $rawFormat = $options->getFormat(true);

        $resources = $options->getResources();
        if (!empty($resources)) {
            $resourceFile = $resources[0];
        } else {
            // fallback to legacy command usage
            $resourceFile = $options->getOutputFile();
        }

        $callable = $this->strategy->getCallable($rawFormat, $resourceFile);

        if (is_array($callable) && str_starts_with($callable[1], 'toSbom')) {
            return $callable($options->getSbomSpec(), $this->immutableCopy);
        }
        if (is_array($callable) && str_starts_with($callable[1], 'fromClass')) {
            return $callable($rawFormat);
        }

        return $callable();
    }

    public function getMimeType(string $resourceFile, ?string $version): string
    {
        return $this->strategy->getMimeType($resourceFile, $version);
    }

    public static function create(string|object $from, Configuration $config, bool $isDecorated): ?string
    {
        if (is_string($from)) {
            if (!class_exists($from)) {
                // Class provided does not exist, or is not readable by Composer Autoloader
                return null;
            }
            $builder = new $from();
        } else {
            $builder = $from;
        }

        if (!$builder instanceof ManifestBuilderInterface) {
            // Your manifest class builder is not compatible.
            return null;
        }

        // The composer.lock and installed.php are optional (e.g. if there is no dependencies installed)
        // but when one is present, the other must be as well
        $composerLock = $config->getComposerLock();
        if (null === $composerLock) {
            // No dependencies installed
            return null;
        }

        $decodedJsonContents = $config->getComposerJson()?->decodedContents;

        $normalizePath = function ($file, $basePath) {
            return ($basePath . DIRECTORY_SEPARATOR . trim($file));
        };

        $basePath = $config->getBasePath();

        if (null !== $decodedJsonContents && array_key_exists('vendor-dir', $decodedJsonContents)) {
            $vendorDir = $normalizePath($decodedJsonContents['vendor-dir'], $basePath);
        } else {
            $vendorDir = $normalizePath('vendor', $basePath);
        }

        $file = implode(DIRECTORY_SEPARATOR, [$vendorDir, 'composer', 'installed.php']);
        if (!file_exists($file) || !is_readable($file)) {
            return null;
        }
        $installedPhp = include $file;

        $decodedJsonLockContents = $config->getComposerLock()?->decodedContents;

        $manifest = $builder(
            [
                'composer.json' => $decodedJsonContents,
                'composer.lock' => $decodedJsonLockContents,
                'installed.php' => (array) $installedPhp,
            ]
        );

        if (!$isDecorated) {
            $manifest = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $manifest);
        }

        return $manifest;
    }

    public function fromClass(string|object $from): ?string
    {
        return self::create($from, $this->config, $this->isDecorated);
    }

    public function toText(): ?string
    {
        return self::create(SimpleTextManifestBuilder::class, $this->config, $this->isDecorated);
    }

    public function toHighlight(): ?string
    {
        return self::create(new DecorateTextManifestBuilder(), $this->config, $this->isDecorated);
    }

    public function toConsole(): ?string
    {
        return self::create(new ConsoleTextManifestBuilder(), $this->config, $this->isDecorated);
    }

    public function toSbom(string $format, string $specVersion, bool $isImmutable): ?string
    {
        try {
            $version = Version::from($specVersion);
        } catch (ValueError $valueError) {
            throw new DomainException(
                sprintf(
                    'Unsupported spec version "%s" for SBOM format. Expected one of these values: %s',
                    $specVersion,
                    implode(', ', array_column(Version::cases(), 'value'))
                ),
                0,
                $valueError
            );
        }
        $spec = SpecFactory::makeForVersion($version);

        $normalizer = match ($format) {
            'xml' => new DomNormalizerFactory($spec),
            'json' => new JsonNormalizerFactory($spec),
            default => throw new DomainException(sprintf('Format "%s" is not supported.', $format)),
        };
        return self::create(
            new SbomManifestBuilder($normalizer, $this->boxVersion, $this->boxManifestVersion, $isImmutable),
            $this->config,
            false
        );
    }

    /**
     * @since Release 4.0.0
     */
    public function toSbomJson(string $specVersion, bool $isImmutable): ?string
    {
        return $this->toSbom('json', $specVersion, $isImmutable);
    }

    /**
     * @since Release 4.0.0
     */
    public function toSbomXml(string $specVersion, bool $isImmutable): ?string
    {
        return $this->toSbom('xml', $specVersion, $isImmutable);
    }
}
