<!-- markdownlint-disable MD013 -->
# About

Main goal of this project is to write a manifest in any [PHP Archive (PHAR)](https://www.php.net/phar)
built with the [BOX](https://github.com/box-project/box) tool.

## Features

- Can generate manifest in [CycloneDX SBOM Standard][cyclonedx] format
- Can generate manifest in a simple key-value pairs plain text format (`key: value`)
- Can generate manifest in a decorated text format (distinguish direct dependencies requirement and other uses)
- Can generate manifest in a custom user format
- Can generate a stub that should be able to display one or all manifests provided by the PHP Archive

## Table Of Contents

1. [How to install the tool](./installation.md)
1. [Getting Started Guide](./getting-started.md)
1. [Learn more with different Use Cases](./UseCases/README.md)
1. [Learn more with the tutorial](./Tutorial/README.md)
1. [How to contribute](./contributing.md)

[cyclonedx]: https://github.com/CycloneDX
