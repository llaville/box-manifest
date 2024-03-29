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

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;
use function str_starts_with;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class Application extends ManifestApplication
{
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

    public function __construct(string $version = '@git-version@')
    {
        if (str_starts_with($version, '@')) {
            $version = '3.x-dev';
        }
        parent::__construct('Manifest', $version);
    }

    public function getLongVersion(): string
    {
        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelperSet()->get(BoxHelper::NAME);

        return sprintf(
            '<info>Box %s</info> version <comment>%s</comment> for Box <comment>%s</comment>',
            $this->getName(),
            $this->getVersion(),
            $boxHelper->getBoxVersion()
        );
    }

    public function getHelp(): string
    {
        return self::$logo . $this->getLongVersion();
    }

    protected function getDefaultHelperSet(): HelperSet
    {
        $helperSet = parent::getDefaultHelperSet();
        $helperSet->set(new BoxHelper());
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
