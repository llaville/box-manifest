<!-- markdownlint-disable MD013 MD033 -->
# BOX Manifest

Main goal of this project is to write a manifest in any [PHP Archive (PHAR)](https://www.php.net/phar)
built with the [BOX](https://github.com/box-project/box) tool.

## Goals

- Add a new command `contrib:add-manifest` to generate any manifest in multiple format (`plain`, `ansi`, `sbom`).
- Include such generation as any other file of your project when your compile the PHP Archive via `box compile` command.

**IMPORTANT** :

Major version `3.0` will not use anymore the [`cweagans/composer-patches`](https://github.com/cweagans/composer-patches)
composer plugin to patch `humbug/box` at install runtime.

## Version Compatibility

| Version            | Status             | Box Project Compatibility |
|--------------------|--------------------|---------------------------|
| `3.0.x`            | Active development | `4.x`                     |
| `2.0.x` to `2.3.x` | Active support     | `4.0.x` to `4.2.x`        |
| `1.0.x` to `1.2.0` | End Of Life        | `3.x`                     |

## Documentation

All the documentation is available on [website](https://llaville.github.io/box-manifest/3.x),
generated from the [docs](https://github.com/llaville/box-manifest/tree/master/docs) folder.

## Contributors

- Laurent Laville (Lead Developer)
