<!-- markdownlint-disable MD013 MD028 -->
# Build your stub

> [!WARNING]
>
> We've dropped legacy commands, but we still show syntax usage to help Users of version 3 for a smooth migration.

=== "Pipeline Command"

    ```shell
    box-manifest make -r console-table.txt -r manifest.txt -r sbom.json --output-stub stub.php stub
    ```

=== "Legacy Command"

    ```shell
    box-manifest manifest:stub -r console-table.txt -r manifest.txt -r sbom.json -o stub.php
    ```

This will create, the `stub.php` file, where manifests order is preserved and deterministic.
