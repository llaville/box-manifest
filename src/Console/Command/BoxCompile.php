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

use KevinGH\Box\Console\Command\Compile;
use KevinGH\Box\Console\Logger\CompilerLogger;
use KevinGH\Box\PharInfo\PharInfo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

use DirectoryIterator;
use PharFileInfo;
use function array_merge;
use function file_exists;
use function file_get_contents;
use function is_string;
use function json_decode;
use function mime_content_type;
use function serialize;
use function simplexml_load_string;
use function sprintf;
use function str_contains;
use function str_starts_with;
use const LIBXML_NOCDATA;

/**
 * @author Laurent Laville
 * @since Release 3.2.0
 */
final class BoxCompile extends Command
{
    public const NAME = 'box:compile';

    private Compile $boxCommand;

    public function __construct(Compile $boxCommand)
    {
        $this->boxCommand = $boxCommand;
        parent::__construct(self::NAME);
    }

    protected function configure(): void
    {
        $configuration = $this->boxCommand->getConfiguration();

        $this->setName(self::NAME)
            ->setDescription($configuration->getDescription())
            ->setDefinition(
                new InputDefinition(
                    array_merge(
                        $configuration->getArguments(),
                        $configuration->getOptions(),
                        [
                            new InputOption(
                                'bootstrap',
                                null,
                                InputOption::VALUE_REQUIRED,
                                'A PHP script that is included before execution',
                            )
                        ]
                    )
                )
            )
            ->setHelp($configuration->getHelp())
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->hasOption('bootstrap')) {
            $filename = $input->getOption('bootstrap');
            if ($filename && file_exists($filename)) {
                include_once $filename;
            }
        }

        $formatter = $output->getFormatter();
        $formatter->setStyle(
            'recommendation',
            new OutputFormatterStyle('black', 'yellow'),
        );
        $formatter->setStyle(
            'warning',
            new OutputFormatterStyle('white', 'red'),
        );

        $io = new IO($input, $output);
        $noDebug = $io->getOption('no-debug')->asBoolean();

        if ($output->isDebug() && $noDebug) {
            // Symfony Runtime Component introduces the `--no-debug` option
            // But native BOX compile command did not support yet (4.3.8) this component
            $newOutput = clone $output;
            $newOutput->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        } else {
            $newOutput = $output;
        }

        $status = $this->boxCommand->execute($io->withOutput($newOutput));

        if (Command::SUCCESS === $status) {
            $this->updateMetadata($io);
        }
        return $status;
    }

    private function updateMetadata(IO $io): void
    {
        /** @var BoxHelper $boxHelper */
        $boxHelper = $this->getHelper(BoxHelper::NAME);

        $config = $boxHelper->getBoxConfiguration(
            $io->isVerbose() ? $io : $io->withOutput(new NullOutput()),
            true,
            $io->getOption(BoxHelper::NO_CONFIG_OPTION)->asBoolean()
        );

        $pharInfo = new PharInfo($config->getOutputPath());
        $phar = $pharInfo->getPhar();
        $manifestDirectory = '.box.manifests';
        $metaFile =  $manifestDirectory . '/.manifests.bin';

        if (!isset($phar[$manifestDirectory])) {
            return;
        }

        $manifests = [];
        foreach (new DirectoryIterator($phar[$manifestDirectory]->getPathname()) as $manifestFileInfo) {
            /** @var PharFileInfo $manifestFileInfo */
            $mimeType = mime_content_type($manifestFileInfo->getFilename());
            $manifests[$manifestFileInfo->getFilename()] = [
                'mime_type' => is_string($mimeType) ? $mimeType : 'application/octet-stream',
                'format' => $this->autoDetectFormat(file_get_contents($manifestFileInfo->getFilename())),
            ];
        }
        $phar[$metaFile] = serialize($manifests);

        $logger = new CompilerLogger($io);
        $logger->log(
            CompilerLogger::QUESTION_MARK_PREFIX,
            'Setting manifests',
        );

        foreach ($manifests as $key => $value) {
            $logger->log(
                CompilerLogger::MINUS_PREFIX,
                "$key <info>></info> {$value['mime_type']} : {$value['format']}",
            );
        }
    }

    private function autoDetectFormat(string $contents): string
    {
        $properties = [];
        $metadataProperties = [];

        if (str_starts_with($contents, '<?xml')) {
            // XML format
            $bom = simplexml_load_string($contents, null, LIBXML_NOCDATA);
            if (isset($bom->metadata->properties)) {
                $metadataProperties = $bom->metadata->properties->property;
            }
        }

        if (str_contains($contents, '$schema')) {
            // JSON format
            $array = json_decode($contents, true);
            $metadataProperties = $array['metadata']['properties'];
        }

        // Whatever format is (XML or JSON)
        foreach ($metadataProperties as $property) {
            $properties[(string) $property['name']] = (string) ($property['value'] ?? $property);
        }

        if (isset($properties['bomFormat']) && isset($properties['specVersion'])) {
            return sprintf('%s v%s', $properties['bomFormat'], $properties['specVersion']);
        }
        return '';
    }
}
