<!-- markdownlint-disable MD013 MD033 -->
# BOX Manifest

[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)
[![GitHub Discussions](https://img.shields.io/github/discussions/llaville/box-manifest)](https://github.com/llaville/box-manifest/discussions)

Main goal of this project is to write a manifest in any [PHP Archive (PHAR)][php-phar] built with the [BOX][box-project] tool.

## Features

Provides a Symfony Console Application with the binary command `box-manifest` that :

- Can generate manifest in [CycloneDX SBOM Standard][cyclonedx] format (`sbom-json` or `sbom-xml`)
- Can generate manifest in a simple key-value pairs `plain` text format (`key: value`)
- Can generate manifest in a decorated text format `console-style` or `console-table` (distinguish direct dependencies requirement and other uses)
- Can generate manifest in a custom user format
- Can generate a stub that should be able to display one or all manifests provided by the PHP Archive
- Can inspect a PHAR to find and display manifests contents

## Version Compatibility

| Version            | Status             | Box Project Compatibility | PHP      |
|--------------------|--------------------|---------------------------|----------|
| `4.0.x`            | Active development | `4.6.x`                   | `>= 8.2` |
| `3.0.x` to `3.5.x` | Active support     | `4.0.x` to `4.3.x`        | `>= 8.1` |
| `2.0.x` to `2.3.x` | End Of Life        | `4.0.x` to `4.2.x`        | `>= 8.1` |
| `1.0.x` to `1.2.0` | End Of Life        | `3.x`                     | `>= 7.4` |

## Documentation

All the documentation is available on [website][docs-website], generated from the [docs][docs-folder] folder.

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
    commit id: "(introduces new make command to replace legacy commands) 734ecb6"
    commit id: "(introduces new inspect command) 36e0540"
    checkout master
    commit id: "upcoming major version" type: HIGHLIGHT tag: "4.0.0"
```

[php-phar]: https://www.php.net/phar
[box-project]: https://github.com/box-project/box
[cyclonedx]: https://github.com/CycloneDX
[docs-folder]: https://github.com/llaville/box-manifest/tree/4.x/docs
[docs-website]: https://llaville.github.io/box-manifest/4.0
