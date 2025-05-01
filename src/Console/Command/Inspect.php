<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Console\Command;

use Bartlett\BoxManifest\Pipeline\AbstractStage;

use Fidry\Console\IO;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Path;

use DirectoryIterator;
use Phar;
use UnexpectedValueException;
use function count;
use function is_array;
use function reset;
use function sprintf;
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

    private Phar $phar; // @phpstan-ignore-line

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

        /** @var array{manifestIndexFile?: string} $pharMetadata */
        $pharMetadata = $phar->getMetadata();

        $manifestIndexFile = $pharMetadata['manifestIndexFile'] ?? '.box.manifests.bin';

        $manifests = [];

        if (isset($phar[$manifestIndexFile])) {
            $manifests = unserialize($phar[$manifestIndexFile]->getContent());
            if (!is_array($manifests)) {
                $io->error(sprintf('The manifest index file "%s" does not contains a valid value.', $manifestIndexFile));
                return Command::FAILURE;
            }
        } else {
            // fallbacks to ".box.manifests" folder entries (if available)
            if (isset($phar[AbstractStage::BOX_MANIFESTS_DIR])) {
                foreach (new DirectoryIterator('phar://' . $phar->getPath() . '/' . AbstractStage::BOX_MANIFESTS_DIR) as $manifestFile) {
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
            $manifestInfo = sprintf('<info>%s</info> (<comment>%s</comment>)', $filename, $mimeType);
            if (empty($inspection)) {
                $manifestInfo = 'Default: ' . $manifestInfo;
            }
            $inspection[] = $manifestInfo;
        }

        if (empty($inspection)) {
            $io->warning(sprintf('The file "%s" does not contains any manifests.', $file));
        } else {
            $manifestsFound = count($manifests);
            $io->success(
                sprintf(
                    'Found %d manifest%s',
                    $manifestsFound,
                    $manifestsFound > 1 ? 's' : ''
                )
            );
            $io->listing($inspection);
        }

        return Command::SUCCESS;
    }
}
