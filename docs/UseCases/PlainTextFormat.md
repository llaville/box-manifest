<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `plain` TEXT format

> [!IMPORTANT]
>
> These commands and results are applied from `examples/app-fixtures` immutable demo folder.
> Must be your current working directory.

## :material-numeric-1-box: With legacy command

> [!WARNING]
>
> We've dropped legacy commands, but we still show syntax usage to help Users of version 3 for a smooth migration.

=== "Command"

    ```shell
    box-manifest manifest:build -f plain
    ```

=== "Output"

    ```text
    root/app-fixtures: 3.x-dev@9661882
    psr/log: 3.0.0
    ```

## :material-numeric-2-box: With pipeline command

=== "Command"

    ```shell
    box-manifest make -r manifest.txt build
    ```

=== ":octicons-file-code-16: `manifest.txt`"

    ```text
    root/app-fixtures: 3.x-dev@9661882
    psr/log: 3.0.0
    ```
