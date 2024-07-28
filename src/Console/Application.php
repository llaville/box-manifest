<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console;

use Bartlett\BoxManifest\Helper\BoxHelper;
use Bartlett\BoxManifest\Helper\ManifestHelper;

use Composer\InstalledVersions;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use OutOfBoundsException;
use function sprintf;
use function substr;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class Application extends SymfonyApplication
{
    public const APPLICATION_NAME = 'BOX Manifest';
    public const PACKAGE_NAME = 'bartlett/box-manifest';

    /**
     * @link http://patorjk.com/software/taag/#p=display&f=Slant&t=Box%20Manifest
     * editorconfig-checker-disable
     */
    private static string $logo = "
    ____                __  ___            _ ____          __
   / __ )____  _  __   /  |/  /___ _____  (_) __/__  _____/ /_
  / __  / __ \| |/_/  / /|_/ / __ `/ __ \/ / /_/ _ \/ ___/ __/
 / /_/ / /_/ />  <   / /  / / /_/ / / / / / __/  __(__  ) /_
/_____/\____/_/|_|  /_/  /_/\__,_/_/ /_/_/_/  \___/____/\__/

";

    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        if ('UNKNOWN' === $name) {
            $name = self::APPLICATION_NAME;
        }
        if ('UNKNOWN' === $version) {
            $version = self::getPrettyVersion();
        }
        parent::__construct($name, $version);
    }

    public static function getPrettyVersion(): string
    {
        foreach (InstalledVersions::getAllRawData() as $installed) {
            if (!isset($installed['versions'][self::PACKAGE_NAME])) {
                continue;
            }

            $version = $installed['versions'][self::PACKAGE_NAME]['pretty_version']
                ?? $installed['versions'][self::PACKAGE_NAME]['version']
                ?? 'dev'
            ;

            $aliases = $installed['versions'][self::PACKAGE_NAME]['aliases'] ?? [];

            return sprintf(
                '%s@%s',
                $aliases[0] ?? $version,
                substr(InstalledVersions::getReference(self::PACKAGE_NAME), 0, 7)
            );
        }

        throw new OutOfBoundsException(sprintf('Package "%s" is not installed', self::PACKAGE_NAME));
    }

    public function getHelp(): string
    {
        return self::$logo . $this->getLongVersion();
    }

    protected function getDefaultHelperSet(): HelperSet
    {
        $helperSet = parent::getDefaultHelperSet();
        $helperSet->set(new BoxHelper());
        $helperSet->set(new ManifestHelper());
        return $helperSet;
    }

    protected function configureIO(InputInterface $input, OutputInterface $output): void
    {
        /** @var ManifestHelper $manifestHelper */
        $manifestHelper = $this->getHelperSet()->get(ManifestHelper::NAME);
        $manifestHelper->configureOption($this->getDefinition());

        parent::configureIO($input, $output);
    }
}
