<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `Composer Tree` TEXT format

> [!IMPORTANT]
>
> These commands and results are applied from `examples/app-fixtures` immutable demo folder.
> Must be your current working directory.

> [!NOTE]
>
> Available since version 4.4.0

## :material-numeric-1-box: With pipeline command

=== "Command"

    ```shell
    box-manifest make -r manifest.composer.txt build
    ```

=== ":octicons-file-code-16: `manifest.composer.txt`"

    ```text
    psr/log 3.0.0 Common interface for logging libraries
    `--php >=8.0.0
    ```
