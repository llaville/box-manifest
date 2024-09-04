<!-- markdownlint-disable MD013 MD028 -->
# Build your stub

> [!WARNING]
>
> We've dropped legacy commands, but we still show syntax usage to help Users of version 3 for a smooth migration.

=== "Pipeline Command"

    ```shell
    box-manifest make --output-stub stub.php stub
    ```

    > [!CAUTION]
    >
    > When none resources are provided (`--resource|-r`) recommended way,
    > we used list defined into `.box.manifests.bin` meta-data file.
    >
    > === "Meta-data file"
    >
    >    ```
    >    a:3:{s:17:"console-table.txt";s:24:"application/octet-stream";s:12:"manifest.txt";s:10:"text/plain";s:9:"sbom.json";s:38:"application/vnd.sbom+json; version=1.6";}
    >    ```

=== "Legacy Command"

    ```shell
    box-manifest manifest:stub -r console-table.txt -r manifest.txt -r sbom.json -o stub.php
    ```

This will create, the `stub.php` file, where manifests order is preserved and deterministic.
