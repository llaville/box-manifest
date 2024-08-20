<!-- markdownlint-disable MD013 MD028 -->
# Build your stub

To help Users in a smooth usage migration, we have not yet removed legacy commands.

- `manifest:stub` now an alias of `stub` command

So, we are show here both usages with legacy and new pipeline commands !

> [!CAUTION]
>
> We recommend to learn the new pipeline syntax, because legacy commands will be dropped in next version 4.1

=== "Pipeline Command"

    ```shell
    box-manifest make -r console.txt -r manifest.txt -r sbom.json -o stub.php stub
    ```

=== "Legacy Command"

    ```shell
    box-manifest stub -r console.txt -r manifest.txt -r sbom.json -o stub.php
    ```

This will create, the `stub.php` file, where manifests order is preserved and deterministic.
