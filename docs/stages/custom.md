<!-- markdownlint-disable MD013 -->
# Additional stages

## Objective

### How it works

This stage is invoked by the `box-manifest make` command with a PHP class name (fully qualified) as argument.

### When to use it

You should run this command whenever you want to add specific new behavior (not provided) by built-in stages.

## Architecture

This class must follow the `\Bartlett\BoxManifest\Pipeline\StageInterface` contract and should be loadable,
either by your current autoloader, or by using the `--bootstrap|-b` option.

### Example

=== "Command"

    ```shell
    box-manifest make '\MyCustomStage'
    ```

=== ":octicons-file-code-16: MyCustomStage.php"

    ```php
    <?php

    use Bartlett\BoxManifest\Pipeline\AbstractStage;
    use Bartlett\BoxManifest\Pipeline\StageInterface;

    final readonly class MyCustomStage extends AbstractStage implements StageInterface
    {
        public function __invoke(array $payload): array
        {
            $this->io->writeln([
                'Payload :',
                var_export($payload, true),
                sprintf('"%s" was invoked with previous payload from command "%s"', __CLASS__,  $this->command->getName())
            ]);

            return $payload;
        }
    }
    ```
