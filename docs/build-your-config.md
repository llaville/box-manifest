<!-- markdownlint-disable MD013 MD028 -->
# Build your Box config file

As there are no legacy commands to realize this operation, we present here only the new pipeline syntax.

> [!TIP]
>
> We recommend to use the `*.json.dist` suffix file for writing the final Box configuration file.
> But you're free to use whatever naming strategy you want.

=== "Baseline configuration :octicons-file-code-16: my-box.json"

    ```yaml
    {
        "main": "bin/box-manifest",
        "compression": "GZ",
        "directories": ["bin", "src", "vendor"],
        "directories-bin": [
            "vendor/humbug/box/res/requirement-checker"
        ]
    }
    ```

=== "Without baseline config"

    === "Command"

        ```shell
        box-manifest make -r console.txt -r manifest.txt -r sbom.json --output-conf my-box.json.dist configure
        ```

    === ":octicons-file-code-16: my-box.json.dist"

        ```yaml
        {
            "files-bin": [
                "console.txt",
                "manifest.txt",
                "sbom.json",
                ".manifests.bin"
            ]
        }
        ```

=== "With baseline config"

    === "Command"

        ```shell
        box-manifest make -r console.txt -r manifest.txt -r sbom.json -c my-box.json --output-conf my-box.json.dist configure
        ```

    === ":octicons-file-code-16: my-box.json.dist"

        ```yaml
        {
            "main": "bin/box-manifest",
            "compression": "GZ",
            "directories": ["bin", "src", "vendor"],
            "directories-bin": [
                "vendor/humbug/box/res/requirement-checker"
            ],
            "files-bin": [
                "console.txt",
                "manifest.txt",
                "sbom.json",
                ".manifests.bin"
            ]
        }
        ```
