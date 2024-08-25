<!-- markdownlint-disable MD013 MD028 -->
# Compile your PHAR

Box Manifest v4 is a full framework for building and maintaining your PHAR manifests.

So, you have two possibilities to invoke the [`BOX`][box-project] tool, even if the pipeline command is recommended.

=== "Pipeline Command"

    ```shell
    box-manifest make compile
    ```

    > [!NOTE]
    >
    > On normal verbose mode, the `make compile` command prints nothing.
    >
    > Use at least verbose level 2: `-vv`, to have `box compile` output, and verbose level 3: `-vvv`, for debugging purpose.

=== "Standard BOX Command"

    ```shell
    vendor/bin/box compile
    ```

[box-project]: https://github.com/box-project/box
