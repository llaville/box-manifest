<!-- markdownlint-disable MD013 MD033 MD046 -->
# Strategies

## Default strategy

This strategy identify what resource (filename) is associated to a manifest and the target format to build.

|           Filename            |     Format      |          Mime Type          |
|:-----------------------------:|:---------------:|:---------------------------:|
|      `console-style.txt`      | `console-style` | `application/octet-stream`  |
|      `console-table.txt`      | `console-table` | `application/octet-stream`  |
| `plain.txt` or `manifest.txt` |     `plain`     |        `text/plain`         |
|  `sbom.json` or `*.cdx.json`  |   `sbom-json`   | `application/vnd.sbom+json` |
|   `sbom.xml` or `*.cdx.xml`   |   `sbom-xml`    | `application/vnd.sbom+xml`  |
|         `custom.bin`          |   User class    | `application/octet-stream`  |

## PostInstall strategy

If you want to auto-update your manifests, each time your update your dependencies, this strategy is your best friend !

> [!NOTE]
>
> Learn more with [Composer Scripts feature](https://getcomposer.org/doc/articles/scripts.md),
> and how to configure it with [extra data](https://getcomposer.org/doc/04-schema.md#extra).

It's easier than running Box Manifest pipeline commands. Just add the following setting into your `composer.json` project file.

```json
{
    "scripts": {
        "post-update-cmd": "Bartlett\\BoxManifest\\Composer\\PostInstallStrategy::postUpdate"
    }
}
```

> [!CAUTION]
>
> Because BOX v4 will raise [`Fatal error: Cannot declare class Composer\InstalledVersions, because the name is already in use`][box-991-issue],
> please use the `vendor/bin/composer` command installed with BOX Manifest rather than your Composer platform version.

### Configuration

| Option                          | Description                                                        | Default           |
|---------------------------------|--------------------------------------------------------------------|-------------------|
| `box-project`<br>`config-file`  | BOX configuration file that must identify a [map][box-map] setting | `box.json.dist`   |
| `box-project`<br>`resource-dir` | Identify folder where your manifests are stored into the PHAR      | `.box.manifests/` |

> [!TIP]
>
> `box-project` > `resource-dir` with `null` value is equivalent to default directory `.box.manifests/`

For example:

=== ":octicons-file-code-16: composer.json"

    ```json
    {
        "scripts": {
            "post-update-cmd": "Bartlett\\BoxManifest\\Composer\\PostInstallStrategy::postUpdate"
        },
        "extra": {
            "box-project": {
                "config-file": "my-box.json",
                "resource-dir": ".my-manifests/"
            }
        }
    }
    ```

=== ":octicons-file-code-16: my-box.json"

    ```json
    {
        "main": "bin/box-manifest",
        "compression": "GZ",
        "directories": ["bin", "src", "vendor"],
        "directories-bin": [
            "vendor/humbug/box/res/requirement-checker",
            "resources"
        ],
        "files": [
            "autoload.php",
            "bootstrap.php"
        ],
        "files-bin": [
            "vendor/humbug/php-scoper/vendor-hotfix/.gitkeep",
            "console-table.txt",
            "plain.txt",
            "sbom.json",
            ".box.manifests.bin"
        ],
        "stub": "stub.php",
        "map": [
            { "console-table.txt": ".my-manifests/console-table.txt" },
            { "plain.txt": ".my-manifests/plain.txt" },
            { "sbom.json": ".my-manifests/sbom.json" }
        ],
        "blacklist": [
            "fixtures",
            "tests",
            "Test",
            "doc",
            "dist",
            "vendor-bin"
        ]
    }
    ```

=== "Manifests updated"

    - `console-table.txt` : Symfony Console Table format
    - `plain.txt` : Plain text format
    - `sbom.json` : CycloneDX SBOM JSON format

[box-map]: https://box-project.github.io/box/configuration/#map-map
[box-991-issue]: https://github.com/box-project/box/issues/991
