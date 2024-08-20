<!-- markdownlint-disable MD013 -->
# Compilation stage

This stage is in charge to compile an application into a PHAR

## Objective

### How it works

This stage is invoked by the `box-manifest make` command with `compile` as argument.

- `--config|-c` help to identify the final configuration file path to use for PHAR packaging settings

> [!CAUTION]
>
> If a configuration file is not specified through the `--config|-c` option,
> one of the following files will be used (in order): `box.json`, `box.json.dist`,
> unless you explicitly specify the `--no-config` option.

### When to use it

You should run this command whenever you want to compile the PHAR with the `stub` and all manifests shipped inside.

## Architecture

The `\Bartlett\BoxManifest\Pipeline\CompileStage` class is in charge to run the standard `vendor/bin/box compile` command.
