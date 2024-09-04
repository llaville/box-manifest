<!-- markdownlint-disable MD013 MD029 MD033 -->
# Generate a final BOX configuration file

Before to compile your [PHP Archive (PHAR)][php-phar] with the [BOX][box-project] tool,
you need to have a BOX configuration file that :

- identify all manifest files to include as binary files with [`files-bin`][box-files] setting.
- (optionally but recommended) store manifest files in a directory rather than in root archive with [`map`][box-map] setting.

> [!TIP]
>
> You can complete an existing configuration file, rather than building a new one from scratch.
>
> Don't forget to specify option `--config /path/to/any-box.json` on following command invocations.

## :material-numeric-1-box: Store manifests in default directory

=== "Command"

    ```shell
    box-manifest make configure
    ```

=== "Output"

    Here, we supposed to have previously generated `console-table.txt` and `manifest.txt` resources.

    ```json
    {
        "files-bin": [
            "console-table.txt",
            "manifest.txt",
            ".box.manifests.bin",
        ],
        "map": [
            {
                "console-table.txt": ".box.manifests/console-table.txt"
            },
            {
                "manifest.txt": ".box.manifests/manifest.txt"
            }
        ],
        "stub": null
    }
    ```

## :material-numeric-2-box: Store manifests in a directory rather than in root archive

=== "Command"

    ```shell
    box-manifest make --resource-dir '.my_manifests/' configure
    ```

=== "Output"

    Here, we supposed to have previously generated `console-table.txt` and `manifest.txt` resources.

    ```json
    {
        "files-bin": [
            "console-table.txt",
            "manifest.txt",
            ".box.manifests.bin",
        ],
        "map": [
            {
                "console.txt": ".my_manifests/console.txt"
            },
            {
                "manifest.txt": ".my_manifests/manifest.txt"
            }
        ],
        "stub": null
    }
    ```

> [!TIP]
>
> If you want to keep your manifests store in root of the PHP Archive, please specify `--resource-dir '/'`

## :material-numeric-3-box: Identify the PHAR bootstrapping file ([`stub`][box-stub] BOX setting)

To do so, you have to specify `--output-stub` option with filename that should be previously built (or not).

=== "Command"

    ```shell
    box-manifest make --resource-dir '.my_manifests/' --output-stub app-fixtures-stub.php configure
    ```

=== "Output"

    Here, we supposed to have previously generated `console-table.txt` and `manifest.txt` resources.

    ```json
    {
        "files-bin": [
            "console-table.txt",
            "manifest.txt",
            ".box.manifests.bin",
        ],
        "map": [
            {
                "console.txt": ".my_manifests/console.txt"
            },
            {
                "manifest.txt": ".my_manifests/manifest.txt"
            }
        ],
        "stub": "app-fixtures-stub.php"
    }
    ```

[php-phar]: https://www.php.net/phar
[box-project]: https://github.com/box-project/box
[box-files]: https://box-project.github.io/box/configuration/#files-files-and-files-bin
[box-map]: https://box-project.github.io/box/configuration/#map-map
[box-stub]: https://box-project.github.io/box/configuration/#stub-stub
