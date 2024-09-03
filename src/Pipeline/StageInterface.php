<?php declare(strict_types=1);
/**
 * This file is part of the BoxManifest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\BoxManifest\Pipeline;

use Fidry\Console\IO;

use KevinGH\Box\Configuration\Configuration;

use Psr\Log\LoggerInterface;

use Symfony\Component\Console\Command\Command;

/**
 * @author Laurent Laville
 * @since Release 4.0.0
 */
interface StageInterface
{
    public const BUILD_STAGE = 'build';
    public const STUB_STAGE = 'stub';
    public const CONFIGURE_STAGE = 'configure';
    public const COMPILE_STAGE = 'compile';
    public const STDOUT = 'php://stdout';

    public static function create(IO $io, Command $command, LoggerInterface $logger, array $context): static;

    /**
     * @param array{
     *     configuration: Configuration,
     *     ansiSupport: bool,
     *     immutableCopy: bool,
     *     template: string,
     *     resources: string[],
     *     resourceDir: string|null,
     *     map: array<array<string>>|null,
     *     versions: array<string, string>,
     *     output: string,
     *     outputFormat: string,
     *     sbomSpec: string,
     *     outputStub: string|null,
     *     outputConf: string|null,
     *     configurationFile: string|null
     * } $payload
     * @return array{
     *     configuration: Configuration,
     *     ansiSupport: bool,
     *     immutableCopy: bool,
     *     template: string,
     *     resources: string[],
     *     resourceDir: string|null,
     *     map: array<array<string>>|null,
     *     versions: array<string, string>,
     *     output: string,
     *     outputFormat: string,
     *     sbomSpec: string,
     *     outputStub: string|null,
     *     outputConf: string|null,
     *     configurationFile: string|null
     * }
     */
    public function __invoke(array $payload): array;
}
