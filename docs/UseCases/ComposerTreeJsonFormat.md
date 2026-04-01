<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `Composer Tree` JSON format

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
    box-manifest make -r manifest.composer.json build
    ```

=== ":octicons-file-code-16: `manifest.composer.json`"

    ```json
    {
        "installed": [
            {
                "name": "psr/log",
                "version": "3.0.0",
                "description": "Common interface for logging libraries",
                "requires": [
                    {
                        "name": "php",
                        "version": ">=8.0.0"
                    }
                ]
            }
        ]
    }
    ```
