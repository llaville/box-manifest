<!-- markdownlint-disable MD013 MD028 -->
# Build your Box config file

As there are no legacy commands to realize this operation, we present here only the new pipeline syntax.

> [!TIP]
>
> We recommend to use the `*.json.dist` suffix file for writing the final Box configuration file.
> But you're free to use whatever naming strategy you want.

> [!WARNING]
>
> If you want to use the `-d|--working-dir` option of `make` command to change current working directory at runtime,
> you should disable the `dump-autoload` setting, due to BOX [issue 580][box-issue-580-2326577684].

=== "Baseline configuration :octicons-file-code-16: my-box.json"

    ```yaml
    {
        "main": "index.php",
        "compression": "GZ",
        "directories": ["bin", "src", "vendor"]
    }
    ```

=== "Without baseline config"

    === "Command"

        ```shell
        box-manifest make --output-conf my-box.json.dist configure
        ```

    === ":octicons-file-code-16: my-box.json.dist"

        ```yaml
        {
            "files-bin": [
                "console-table.txt",
                "manifest.txt",
                "sbom.json",
                ".box.manifests.bin"
            ],
            "map": [
                {
                    "console-table.txt": ".box.manifests/console-table.txt"
                },
                {
                    "manifest.txt": ".box.manifests/manifest.txt"
                },
                {
                    "sbom.json": ".box.manifests/sbom.json"
                }
            ],
            "stub": null
        }
        ```

=== "With baseline config"

    === "Command"

        ```shell
        box-manifest make -c my-box.json --output-conf my-box.json.dist configure
        ```

    === ":octicons-file-code-16: my-box.json.dist"

        ```yaml
        {
            "main": "index.php",
            "compression": "GZ",
            "directories": [
                "bin",
                "src",
                "vendor"
            ],
            "files-bin": [
                "console-table.txt",
                "manifest.txt",
                "sbom.json",
                ".box.manifests.bin"
            ],
            "map": [
                {
                    "console-table.txt": ".box.manifests/console-table.txt"
                },
                {
                    "manifest.txt": ".box.manifests/manifest.txt"
                },
                {
                    "sbom.json": ".box.manifests/sbom.json"
                }
            ],
            "stub": null
        }
        ```

[box-issue-580-2326577684]: https://github.com/box-project/box/issues/580#issuecomment-2326577684
