<!-- markdownlint-disable MD013 -->
# Configure stage

This stage is in charge to create or update the final BOX config file.

## Objective

### How it works

This stage is invoked by the `box-manifest make` command with `configure` as argument.

- `--config|-c` help to identify the alternative configuration file path as baseline for PHAR packaging settings

> [!CAUTION]
>
> If a configuration file is not specified through the `--config|-c` option,
> one of the following files will be used (in order): `box.json`, `box.json.dist`,
> unless you explicitly specify the `--no-config` option.

### When to use it

You should run this command whenever you want to create or update the final PHAR packaging settings file.

## Architecture

The `\Bartlett\BoxManifest\Pipeline\ConfigureStage` class is in charge to modify the PHAR packaging settings file contents.
