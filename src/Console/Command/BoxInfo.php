<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Bartlett\BoxManifest\Helper\BoxHelper;

use Fidry\Console\Input\IO;

use KevinGH\Box\Console\Command\Info;
use KevinGH\Box\PharInfo\PharInfo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function file_get_contents;
use function sprintf;
use function unserialize;

/**
 * @author Laurent Laville
 * @since Release 3.2.0
 */
final class BoxInfo extends Command
{
    use BoxCommandAware;

    public const NAME = 'box:info';

    public function __construct(Info $boxCommand)
    {
        $this->boxCommand = $boxCommand;
        parent::__construct(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new IO($input, $output);
        $noDebug = $io->getOption('no-debug')->asBoolean();

        if ($output->isDebug() && $noDebug) {
            // Symfony Runtime Component introduces the `--no-debug` option
            // But native BOX info command did not support yet (4.3.8) this component
            $newOutput = clone $output;
            $newOutput->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        } else {
            $newOutput = $output;
        }

        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelper(BoxHelper::NAME);

        $boxHelper->checkPhpSettings($io->withOutput($newOutput));

        $pharFile = $io->getArgument('phar')->asNullableString();

        if ($pharFile) {
            $phar = (new PharInfo($pharFile))->getPhar();
            $metaFile = '.box.manifests/.manifests.bin';
            if (isset($phar[$metaFile])) {
                $manifests = unserialize(file_get_contents($phar[$metaFile]->getPathname()));
            } else {
                $manifests = [];
            }
        }

        $status = $this->boxCommand->execute($io->withOutput($newOutput));

        if (Command::SUCCESS === $status && $pharFile) {
            $io->newLine();
            $this->renderManifests($manifests, $io);
        }

        return $status;
    }

    /**
     * @param array<string, string[]> $manifests
     */
    private function renderManifests(array $manifests, IO $io): void
    {
        if (empty($manifests)) {
            $io->writeln('<comment>Manifests:</comment> None');
        } else {
            $io->writeln('<comment>Manifests:</comment>');
        }

        foreach ($manifests as $filename => $meta) {
            $io->writeln(sprintf('- <info>%s</info>', $filename));
            foreach ($meta as $key => $value) {
                $io->writeln(sprintf('  > <comment>%s</comment> : %s', $key, $value));
            }
        }
    }
}
