<!-- markdownlint-disable MD013 MD033 -->
# Built-in stages

BOX Manifest started out as a simple CLI application,
but has since evolved into a full-fledged framework for building and maintaining PHAR manifests.

This great improvement since version 4 is possible by implementing the pipeline pattern
with [`league/pipeline`][league-pipeline] Composer Package.

- **Modularity:** Built-in stages are designed to be modular, so that they can be easily combined to implement sophisticated pipelines.

- **Interoperability:** Built-in stages are designed to be as compatible, so they can be used in combination with other stages, including third-party stages

- **Immutability:** Pipelines are implemented as immutable stage chains. When you pipe a new stage, a new pipeline will be created with the added stage.
This makes pipelines easy to reuse, and minimizes side effects.

## Building

The following stages are designed to help you set up a manifest list and the PHAR bootstrapping file.

<div class="grid cards" markdown>

- :material-file-plus: **[Built-in build stage](./build.md)**

    ---

    You are free to add zero or more of manifest file
    in specific (built-in or custom) format.

- :boot: **[Built-in stub stage](./stub.md)**

    ---

    You are free to add a PHAR [`stub`][phar-stub] file.
    This file is the PHAR bootstrapping file, i.e. the very first file executed whenever the PHAR is executed.

    The default PHAR stub file can be used but Box Manifest also propose a couple of options to customize the stub used.

</div>

## Configuration

The following stage is designed to help you manage the BOX configuration file.

<div class="grid cards" markdown>

- :material-archive-settings: **[Built-in configure stage](./configure.md)**

    ---

    You are free to set up a final [Box config][box-configuration] file from a baseline (existing file) or not.

</div>

## Compilation

The following stage is designed to help you shipping manifest files and stub in the PHAR.

<div class="grid cards" markdown>

- :compression: **[Built-in compilation stage](./compilation.md)**

    ---

    Box Manifest act as a bridge to standard `vendor/bin/box compile` command.

</div>

[league-pipeline]: https://github.com/thephpleague/pipeline
[phar-stub]: https://www.php.net/manual/en/phar.fileformat.stub.php
[box-configuration]: https://box-project.github.io/box/configuration/
