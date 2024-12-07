<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Helper;

use Fidry\Console\IO;

use KevinGH\Box\Configuration\NoConfigurationFound;
use KevinGH\Box\Console\ConfigurationLocator;
use KevinGH\Box\Json\Json;

use Seld\JsonLint\ParsingException;

use Symfony\Component\Filesystem\Path;

use Webmozart\Assert\Assert;

use stdClass;
use function array_merge;
use function dirname;
use function file_exists;
use function getcwd;
use function implode;
use function is_bool;
use function is_string;
use function realpath;

/**
 * @author Laurent Laville
 * @since Release 4.1.0
 */
final class BoxConfigurationHelper
{
    public const NO_CONFIG_OPTION = 'no-config';
    public const CONFIG_PARAM = 'config';

    private const BASE_PATH_KEY = 'base-path';
    private const MAIN_KEY = 'main';
    private const DEFAULT_MAIN_SCRIPT = 'index.php';

    private stdClass $rawConfig;

    private ?string $configPath = null;

    /**
     * @throws ParsingException
     */
    public function __construct(
        IO $io,
        ?string $boxManifestVersion = null,
        Json $json = new Json()
    ) {
        $assocConfig = [];

        // @link https://box-project.github.io/box/configuration/#alias-alias
        $assocConfig['alias'] = 'box-auto-generated-alias.phar';

        // By default, the Box Manifest banner is used.
        // @link https://box-project.github.io/box/configuration/#banner-banner
        $assocConfig['banner'] = 'Generated by BOX Manifest ' . ($boxManifestVersion ?? '@dev') . "\n\n"
            . '@link https://github.com/llaville/box-manifest'
        ;
        // By default, this line is used
        // @link https://box-project.github.io/box/configuration/#shebang-shebang
        $assocConfig['shebang'] = '#!/usr/bin/env php';

        // @link https://box-project.github.io/box/configuration/#intercept-intercept
        $assocConfig['intercept'] = false;

        // @link https://box-project.github.io/box/configuration/#check-requirements-check-requirements
        $assocConfig['checkRequirements'] = true;

        // @link https://box-project.github.io/box/configuration/#map-map
        $assocConfig['map'] = [];

        if (false === $io->getTypedOption(self::NO_CONFIG_OPTION)->asBoolean()) {
            $this->configPath = $this->getConfigPath($io);
        }

        if (null !== $this->configPath) {
            /** @var array<string, mixed> $fileConfig */
            $fileConfig = $json->decodeFile($this->configPath, true);
            $assocConfig = array_merge($assocConfig, $fileConfig);
        }

        // @link https://box-project.github.io/box/configuration/#base-path-base-path
        // @phpstan-ignore-next-line
        $assocConfig[self::BASE_PATH_KEY] = $this->retrieveBasePath($this->configPath, $assocConfig) ?: '.';

        $composerJsonPath = $assocConfig[self::BASE_PATH_KEY] . '/composer.json';

        if (file_exists($composerJsonPath)) {
            /** @var array<string, mixed> $decodedComposerJson */
            $decodedComposerJson = $json->decodeFile($composerJsonPath, true);
            $bin = $decodedComposerJson['bin'] ?? [];
            $firstBin = current((array) $bin) ?: null;
        }

        $main = $assocConfig[self::MAIN_KEY] ?? null;

        // @link https://box-project.github.io/box/configuration/#main-main
        if (false !== $main) {
            $assocConfig[self::MAIN_KEY] = $this->retrieveMainScriptPath(
                // @phpstan-ignore argument.type
                $assocConfig,
                // @phpstan-ignore argument.type
                $firstBin ?? null
            );
        }

        $this->rawConfig = (object) $assocConfig;
    }

    public function dump(): stdClass
    {
        return $this->rawConfig;
    }

    public function getConfigurationFile(): ?string
    {
        return $this->configPath;
    }

    public function getMainScript(): ?string
    {
        return $this->rawConfig->main ?: null;
    }

    public function getAlias(): string
    {
        return $this->rawConfig->alias;
    }

    public function getBanner(): string
    {
        $banner = $this->rawConfig->banner ?: '';
        if (is_string($banner)) {
            return $banner;
        }
        return implode("\n", $banner);
    }

    public function getShebang(): string
    {
        return $this->rawConfig->shebang ?: '';
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getMap(): array
    {
        return $this->rawConfig->map;
    }

    public function withInterceptFileFunctions(): bool
    {
        return $this->rawConfig->intercept;
    }

    public function withCheckRequirements(): bool
    {
        return $this->rawConfig->checkRequirements;
    }

    private function getConfigPath(IO $io): ?string
    {
        try {
            /** @var string $configPath */
            $configPath = $io->getInput()->getOption(self::CONFIG_PARAM);
            $configPath ??= ConfigurationLocator::findDefaultPath();
        } catch (NoConfigurationFound) {
            return null;
        }
        return $configPath;
    }

    /**
     * @param array{base-path?: string|null} $assocConfig
     */
    private function retrieveBasePath(?string $file, array $assocConfig): false|string
    {
        if (null === $file) {
            return getcwd();
        }

        if (false === isset($assocConfig[self::BASE_PATH_KEY])) {
            return realpath(dirname($file));
        }

        $basePath = $assocConfig[self::BASE_PATH_KEY];

        if (!is_string($basePath)) {
            return false;
        }

        $basePath = trim($basePath);

        Assert::directory(
            $basePath,
            'The base path %s is not a directory or does not exist.',
        );

        return realpath($basePath);
    }

    /**
     * @param array{base-path: string, main? :string|false|null} $assocConfig
     */
    private function retrieveMainScriptPath(array $assocConfig, ?string $firstBin): ?string
    {
        $basePath = $assocConfig[self::BASE_PATH_KEY];

        if (null !== $firstBin) {
            $firstBin = $this->normalizePath($firstBin, $basePath);
        }

        if (isset($assocConfig[self::MAIN_KEY])) {
            $main = $assocConfig[self::MAIN_KEY];

            if (is_string($main)) {
                $main = $this->normalizePath($main, $basePath);
            }
        } else {
            $main = $firstBin ?? $this->normalizePath(self::DEFAULT_MAIN_SCRIPT, $basePath);
        }

        if (is_bool($main)) {
            Assert::false(
                $main,
                'Cannot "enable" a main script: either disable it with `false` or give the main script file path.',
            );

            return null;
        }

        Assert::file($main);

        return $main;
    }

    private function normalizePath(string $file, string $basePath): string
    {
        return Path::makeRelative(trim($file), $basePath);
    }
}
