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

Other formats are available and are auto-discoverable. Here are the rules applied :

|           Filename            |     Format      |
|:-----------------------------:|:---------------:|
|      `console-style.txt`      | `console-style` |
|      `console-table.txt`      | `console-table` |
| `plain.txt` or `manifest.txt` |     `plain`     |
|  `sbom.json` or `*.cdx.json`  |   `sbom-json`   |
|   `sbom.xml` or `*.cdx.xml`   |   `sbom-xml`    |
|         `custom.bin`          |   User class    |

> CycloneDX [Recognized file patterns][cdx-recognized-file-patterns]

> [!TIP]
>
> **Architecture:** These rules came from `\Bartlett\BoxManifest\Composer\DefaultStrategy` applied by default.

[cdx-recognized-file-patterns]: https://cyclonedx.org/specification/overview/#recognized-file-patterns
