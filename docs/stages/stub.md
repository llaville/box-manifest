<!-- markdownlint-disable MD013 -->
# Stub stage

This stage is in charge to create or update the PHAR bootstrapping file, also called `stub`.

## Objective

### How it works

This stage is invoked by the `box-manifest make` command with `stub` as argument.

- `--output-stub` should identify the target file where to write stub PHP code contents.
- `--template|-t` help to identify the PHP template file to customize the stub.

### When to use it

You should run this command whenever you want to create or update the PHAR bootstrapping file.

## Architecture

The `\Bartlett\BoxManifest\Pipeline\StubStage` class is in charge to build the stub file.
