<!-- markdownlint-disable MD013 MD033 -->
# Manifest in symfony console table format

> [!IMPORTANT]
>
> These commands and results are applied from `examples/app-fixtures` immutable demo folder.
> Must be your current working directory.

## :material-numeric-1-box: With legacy command

> [!WARNING]
>
> We've dropped legacy commands, but we still show syntax usage to help Users of version 3 for a smooth migration.

=== "Command"

    ```shell
    box-manifest manifest:build -f console
    ```

=== "Output"

    ![console format](../assets/images/app-fixtures-console.png)

## :material-numeric-2-box: With pipeline command

=== "Command"

    ```shell
    box-manifest make -r console-table.txt build
    ```

=== "Output"

    ![console format](../assets/images/app-fixtures-console.png)
