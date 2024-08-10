<!-- markdownlint-disable MD013 MD033 -->
# BOX Manifest

Main goal of this project is to write a manifest in any [PHP Archive (PHAR)](https://www.php.net/phar)
built with the [BOX](https://github.com/box-project/box) tool.

## Features

Provides a Symfony Console Application with the binary command `box-manifest` that is able to :

- generate any manifest in multiple format (`plain`, `ansi`, `console`, `sbom` XML or JSON) even in a custom format.
- generate a custom phar stub that will support `--manifest` option at runtime.
- compile your PHAR with a wrapper around standard BOX compile command (with bootstrapping support: `--bootstrap` option).
- display information about the PHAR extension or file.
- validate the BOX configuration file.

**IMPORTANT** :

Major version 3 will not use anymore the [`cweagans/composer-patches`](https://github.com/cweagans/composer-patches)
composer plugin to patch `humbug/box` at install runtime.

## Version Compatibility

| Version            | Status             | Box Project Compatibility |
|--------------------|--------------------|---------------------------|
| `3.0.x` to `3.5.x` | Active support     | `4.0.x` to `4.3.x`        |
| `2.0.x` to `2.3.x` | End Of Life        | `4.0.x` to `4.2.x`        |
| `1.0.x` to `1.2.0` | End Of Life        | `3.x`                     |

## Documentation

All the documentation is available on [website](https://llaville.github.io/box-manifest/3.x),
generated from the [docs](https://github.com/llaville/box-manifest/tree/master/docs) folder.

## Contributors

- Laurent Laville (Lead Developer)

## Roadmap

```mermaid
%%{init: { 'gitGraph': {'mainBranchName': 'master', 'rotateCommitLabel': false, 'mainBranchOrder': 1}} }%%
gitGraph TB:
    commit id: "5a1721a" type: HIGHLIGHT tag: "3.5.1"
    commit id: "(fixed issue #11) 8f2bd10"
    branch 4.x
    commit id: "(only keep manifest:* commands and removed legacy box:* commands) cb90b40"
    commit id: "(replaced deprecated import about fidry/console) 2794e2d"
    commit id: "(add shorcut for bootstrap option) dd5766f"
    commit id: "(rename format option to output-format) 4d3d306"
    commit id: "(add shortcut for resource option) c2aba93"
    commit id: "(upgrade cyclonedx/cyclonedx-library constraint to use major version 3) 8a1e05d"
    commit id: "(use SBOM spec 1.6 as default for sbom-json and sbom-xml output format) 29e0b2c"
    commit id: "(consider serialNumber as optional and do not stop SBOM generation by Exception) f33bc49"
    commit id: "(add alias on legacy command and renamed it to shortnames) 670500f"
    commit id: "(build ansi format first rather than console with new render) 42e8c93"
    commit id: "(add link to Packagist homepage of each dependency into console table format) 26b3262"
    commit id: "(version of BOX Manifest used to generate stub (from template) is now identified) 39eb52e"
    commit id: "(introduces auto detection by filename ansi.txt) 5a174e2"
    checkout master
    commit id: "upcoming major version" type: HIGHLIGHT tag: "4.0.0"
```
