<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use DirectoryIterator;
use Fidry\Console\IO;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Path;

use Phar;
use UnexpectedValueException;
use function count;
use function reset;
use function sprintf;
use function strip_tags;
use function unserialize;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
final class Inspect extends Command
{
    public const NAME = 'inspect';

    private const HELP = <<<'HELP'
        The <info>%command.name%</info> command will display a PHAR manifest content, or list of manifests available.
        HELP;

    private Phar $phar;

    protected function configure(): void
    {
        $options = [
            new InputArgument(
                'phar',
                InputArgument::REQUIRED,
                'The PHAR file.',
            ),
            new InputArgument(
                'manifest',
                InputArgument::OPTIONAL,
                'A specific manifest file.',
            ),
        ];

        $this->setName(self::NAME)
            ->setDescription('Display a PHAR manifest, or manifest list.')
            ->setDefinition($options)
            ->setHelp(self::HELP)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new IO($input, $output);

        $file = $io->getTypedArgument('phar')->asNonEmptyString();
        $file = Path::canonicalize($file);

        try {
            $phar = new Phar($file);
            $signature = $phar->getSignature();
            if (!$signature) {
                throw new UnexpectedValueException('No valid signature');
            }
        } catch (UnexpectedValueException) {
            $io->error(sprintf('The file "%s" is not a valid PHP Archive.', $file));
            return Command::FAILURE;
        }
        $this->phar = $phar;

        $manifestIndexFile = $this->phar->getMetadata()['manifestIndexFile'] ?? '.box.manifests.bin';

        $manifests = [];

        if (isset($phar[$manifestIndexFile])) {
            $manifests = unserialize($phar[$manifestIndexFile]->getContent());
        } else {
            // fallbacks to ".box.manifests" folder entries (if available)
            if (isset($phar['.box.manifests'])) {
                foreach (new DirectoryIterator('phar://' . $phar->getPath() . '/.box.manifests') as $manifestFile) {
                    $filename = $manifestFile->getFilename();
                    $mimeType = match ($filename) {
                        'manifest.txt' => 'text/plain',
                        'sbom.xml'=> 'application/vnd.cyclonedx+xml',
                        'sbom.json'=> 'application/vnd.cyclonedx+json',
                        default => 'application/octet-stream',
                    };
                    $manifests[$filename] = $mimeType;
                }
            }
        }

        $inspection = [];

        $manifest = $io->getTypedArgument('manifest')->asNullableNonEmptyString();

        foreach ($manifests as $filename => $mimeType) {
            if ($manifest !== null && $filename !== $manifest) {
                continue;
            }
            $inspection[] = sprintf('<info>%s</info>: <comment>%s</comment>', $filename, $mimeType);
        }

        if (empty($inspection)) {
            $io->warning(sprintf('The file "%s" does not contains any manifests.', $file));
        } else {
            $manifestsFound = count($manifests);
            $default = reset($inspection);
            $io->success(
                sprintf(
                    'Found %d manifest%s. Default is %s',
                    $manifestsFound,
                    $manifestsFound > 1 ? 's' : '',
                    strip_tags($default)
                )
            );
            $io->listing($inspection);
        }

        return Command::SUCCESS;
    }
}
