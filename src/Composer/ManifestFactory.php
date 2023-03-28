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
use CycloneDX\Core\Serialization\JsonSerializer;
use CycloneDX\Core\Serialization\XmlSerializer;
use CycloneDX\Core\Spec\SpecFactory;
use CycloneDX\Core\Spec\Version;

use KevinGH\Box\Box;
use KevinGH\Box\Configuration\Configuration;
use function KevinGH\Box\FileSystem\make_path_absolute;

use DomainException;
use InvalidArgumentException;
use ValueError;
use function array_column;
use function array_key_exists;
use function class_exists;
use function file_exists;
use function implode;
use function is_readable;
use function is_string;
use function pathinfo;
use function preg_replace;
use function sprintf;
use const PATHINFO_EXTENSION;

/**
 * @author Laurent Laville
 */
final class ManifestFactory
{
    public function __construct(private Configuration $config, private Box $box, private string $boxVersion, private bool $isDecorated)
    {
    }

    public function build(string $format, ?string $output, string $sbomSpec): ?string
    {
        return match ($format) {
            'auto' => match ($output) {
                null, 'manifest.txt' => $this->toText(),
                'sbom.xml' => $this->toSbom('xml', $sbomSpec),
                'sbom.json' => $this->toSbom('json', $sbomSpec),
                default => match (pathinfo($output, PATHINFO_EXTENSION)) {
                    'xml' => $this->toSbom('xml', $sbomSpec),
                    'json' => $this->toSbom('json', $sbomSpec),
                    '', 'txt' => $this->toText(),
                    default => throw new InvalidArgumentException('Cannot auto-detect format with such output file')
                }
            },
            'plain' => $this->toText(),
            'ansi' => $this->toHighlight(),
            'console' => $this->toConsole(),
            'sbom' => $this->toSbom('xml', $sbomSpec),
            default => class_exists($format)
                ? self::create($format, $this->config, $this->box)
                : throw new InvalidArgumentException(sprintf('Format "%s" is not supported', $format))
        };
    }

    public static function create(string|object $from, Configuration $config, Box $box, bool $isDecorated): ?string
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

        $decodedJsonContents = $config->getDecodedComposerJsonContents();

        $normalizePath = function ($file, $basePath) {
            return make_path_absolute(trim($file), $basePath);
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

        $manifest = $builder(
            [
                'composer.json' => $decodedJsonContents,
                'composer.lock' => $config->getDecodedComposerLockContents(),
                'installed.php' => (array) $installedPhp,
            ]
        );

        if (!$isDecorated) {
            $manifest = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $manifest);
        }

        return $manifest;
    }

    public function toText(): ?string
    {
        return self::create(SimpleTextManifestBuilder::class, $this->config, $this->box, $this->isDecorated);
    }

    public function toHighlight(): ?string
    {
        return self::create(new DecorateTextManifestBuilder(), $this->config, $this->box, $this->isDecorated);
    }

    public function toConsole(): ?string
    {
        return self::create(new ConsoleTextManifestBuilder(), $this->config, $this->box, $this->isDecorated);
    }

    public function toSbom(string $format, string $specVersion): ?string
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

        $serializer = match ($format) {
            'xml' => new XmlSerializer(new DomNormalizerFactory($spec)),
            'json' => new JsonSerializer(new JsonNormalizerFactory($spec)),
            default => throw new DomainException(sprintf('Format "%s" is not supported.', $format)),
        };
        return self::create(new SbomManifestBuilder($serializer, $this->boxVersion), $this->config, $this->box, $this->isDecorated);
    }
}
