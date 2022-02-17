<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Composer;

use Composer\InstalledVersions;
use Composer\Script\Event;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Laurent Laville
 */
final class InstallerScripts
{
    private const REQUIREMENT_CHECKER_DIR = '.requirement-checker';

    public static function postUpdate(Event $event): void
    {
        $boxInstalledPath = InstalledVersions::getInstallPath('humbug/box');

        $requirementCheckerPath = \dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . self::REQUIREMENT_CHECKER_DIR;

        $filesystem = new Filesystem();

        if (!$filesystem->exists($requirementCheckerPath)) {
            try {
                $filesystem->mirror(
                    $boxInstalledPath . DIRECTORY_SEPARATOR . self::REQUIREMENT_CHECKER_DIR,
                    $requirementCheckerPath
                );
            } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at " . $exception->getPath();
                exit(1);
            }
        }
    }
}
