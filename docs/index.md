<!-- markdownlint-disable MD013 -->
# About

Main goal of this project is to write a manifest in any [PHP Archive (PHAR)](https://www.php.net/phar)
built with the [BOX](https://github.com/box-project/box) tool.

## Features

- no changes on default BOX [configuration](https://github.com/box-project/box/blob/master/doc/configuration.md#configuration)
- include a minor `humbug/box` patched version (see [patch](https://github.com/llaville/box-manifest/blob/master/patches/box3metadata.patch) contents)
- use [`metadata`](https://github.com/box-project/box/blob/master/doc/configuration.md#metadata-metadata) setting
to define your callable function in charge to build the manifest string.
- provide one basic manifest builder implementation :
  - a simple key-value pairs text format (`Bartlett\BoxManifest\Composer\Manifest\SimpleTextManifestBuilder`)
- manifest is stored by default in the metadata field of the PHP Archive, and may be retrieved later
with the [`Phar::getMetadata()`](https://www.php.net/manual/en/phar.getmetadata.php) API.
