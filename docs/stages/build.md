<!-- markdownlint-disable MD013 -->
# Build stage

This stage is in charge to create or update all manifests that will be shipped with the PHAR file.

## Objective

### How it works

This stage is invoked by the `box-manifest make` command with `build` as argument, only once for one or more manifests.

- `--resource|-r` should identify each manifest file to create or update.

> [!NOTE]
>
> A resource named `custom.bin` identify a custom format (not included in built-in formats)
> that you must specify with the `--output-format` option (a loadable user class).

### When to use it

You should run this command whenever you want to create or update a manifest file contents in a specific format.

## Architecture

The `\Bartlett\BoxManifest\Pipeline\BuildStage` class is in charge to build all manifests
(whatever format you want: built-in or custom).

In case the built-in formats does not match your needs, you have ability to implement your own format with a user class.
This class must follow the `\Bartlett\BoxManifest\Composer\ManifestBuilderInterface` contract and should be loadable,
either by your current autoloader, or by using the `--bootstrap|-b` option.
