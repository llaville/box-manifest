<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console;

use Bartlett\BoxManifest\Helper\Manifest as ManifestEnum;
use Bartlett\BoxManifest\Helper\BoxHelper;
use Bartlett\BoxManifest\Helper\ManifestHelper;

use Phar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use InvalidArgumentException;
use function sprintf;
use function str_starts_with;

/**
 * @author Laurent Laville
 * @since Release 3.0.0
 */
final class Application extends ManifestApplication
{
    /**
     * @link http://patorjk.com/software/taag/#p=display&f=Standard&t=Manifest
     * editorconfig-checker-disable
     */
    private static string $logo = "
  __  __             _  __           _
 |  \/  | __ _ _ __ (_)/ _| ___  ___| |_
 | |\/| |/ _` | '_ \| | |_ / _ \/ __| __|
 | |  | | (_| | | | | |  _|  __/\__ \ |_
 |_|  |_|\__,_|_| |_|_|_|  \___||___/\__|

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
            '<comment>%s</comment><info>Box %s</info> version <comment>%s</comment> for Box <comment>%s</comment>',
            self::$logo,
            $this->getName(),
            $this->getVersion(),
            $boxHelper->getBoxVersion()
        );
    }

    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        if (Phar::running() && true === $input->hasParameterOption('--manifest', true)) {
            $resource = $input->getParameterOption('--manifest', null, true);
            if (null === $resource) {
                $resources = ManifestEnum::values();
            } else {
                $enum = ManifestEnum::tryFrom($resource);
                if (null === $enum) {
                    throw new InvalidArgumentException(sprintf('Manifest "%s" is not provided by this PHP Archive', $resource));
                }
                $resources = [$resource];
            }
            /** @var ManifestHelper $manifestHelper */
            $manifestHelper = $this->getHelperSet()->get(ManifestHelper::NAME);
            $output->writeln($manifestHelper->get($resources) ?? 'No Manifest found.');
            return Command::SUCCESS;
        }
        return parent::doRun($input, $output);
    }

    protected function getDefaultHelperSet(): HelperSet
    {
        $helperSet = parent::getDefaultHelperSet();
        $helperSet->set(new BoxHelper());
        return $helperSet;
    }

    protected function configureIO(InputInterface $input, OutputInterface $output): void
    {
        if ($this->getHelperSet()->has(ManifestHelper::NAME)) {
            /** @var ManifestHelper $manifestHelper */
            $manifestHelper = $this->getHelperSet()->get(ManifestHelper::NAME);
            $manifestHelper->configureOption($this->getDefinition());
        }
        parent::configureIO($input, $output);
    }
}
