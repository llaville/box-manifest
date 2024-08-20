<!-- markdownlint-disable MD013 MD028 -->
# Build your manifests

To help Users in a smooth usage migration, we have not yet removed legacy commands.

- `manifest:build` now an alias of `build` command

So, we are show here both usages with legacy and new pipeline commands !

> [!CAUTION]
>
> We recommend to learn the new pipeline syntax, because legacy commands will be dropped in next version 4.1

=== "Pipeline Command"

    ```shell
    box-manifest make -r manifest.txt build
    ```

=== "Legacy Command"

    ```shell
    box-manifest build -f plain -o manifest.txt
    ```

This will create, the `manifest.txt` file, with a plain text format of your dependencies inventory.
