<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `console` symfony table format

> [!IMPORTANT]
>
> These commands and results are applied from `examples/app-fixtures` immutable demo folder.
> Must be your current working directory.

## :material-numeric-1-box: With legacy command

=== "Command"

    ```shell
    box-manifest build -f console
    ```

=== "Output"

    ![console format](../assets/images/app-fixtures-console.png)


## :material-numeric-2-box: With new pipeline command

=== "Command"

    ```shell
    box-manifest make -r console.txt build
    ```

=== "Output"

    ![console format](../assets/images/app-fixtures-console.png)
