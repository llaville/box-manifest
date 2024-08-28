<!-- markdownlint-disable MD013 MD024 MD046 -->
# Inspect

Once you've built your PHP Archive, you'll be happy to retrieve easily what manifests are embedded,
and what formats are available.

This is the goal of the new `inspect` command.

## PHAR without manifests

### :material-numeric-1-box: Pre-Condition

Suppose you've built BOX Manifest with the `box.json` config file

=== "Pipeline Command"

    ```shell
    box-manifest make compile -c box.json
    ```

=== "Standard BOX Command"

    ```shell
    vendor/bin/box compile -c box.json
    ```

### :material-numeric-2-box: Inspection results

=== "Command"

    ```shell
    box-manifest inspect bin/box-manifest.phar
    ```

=== "Output"

    ![none manifests](./assets/images/inspect--warning.png)

## PHAR with manifests

### :material-numeric-1-box: Pre-Condition

Suppose you've built BOX Manifest with the `box.json.dist` config file

=== "Pipeline Command"

    ```shell
    box-manifest make compile -c box.json.dist
    ```

=== "Standard BOX Command"

    ```shell
    vendor/bin/box compile -c box.json.dist
    ```

### :material-numeric-2-box: Inspection results

=== "Command"

    ```shell
    box-manifest inspect bin/box-manifest.phar
    ```

=== "Output"

    ![some manifests](./assets/images/inspect--success.png)

> [!TIP]
>
> Remember that when you'll invoke the PHAR distribution with `--manifest` option (and no value),
> you'll show only the default (first one in list).
>
> In this context, we will display contents of `console-table.txt` file.
>
> For others, specify which one, with either :
>
> :material-numeric-1-box: Plain text format
>
> ```shell
> bin/box-manifest.phar --manifest plain.txt
> ```
>
> :material-numeric-2-box: CycloneDX SBOM JSON format
>
> ```shell
> bin/box-manifest.phar --manifest sbom.json
> ```
