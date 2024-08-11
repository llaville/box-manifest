<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `sbom` JSON format

> [!IMPORTANT]
>
> These commands and results are applied from `examples/app-fixtures` immutable demo folder.
> Must be your current working directory.

## :material-numeric-1-box: With legacy command

=== "Command"

    ```shell
    box-manifest build -f sbom-json
    ```

=== "Output"

    ```json
    {
        "$schema": "http://cyclonedx.org/schema/bom-1.6.schema.json",
        "bomFormat": "CycloneDX",
        "specVersion": "1.6",
        "serialNumber": "urn:uuid:bb11a687-9725-4c28-b8c0-1a15377274b5",
        "version": 1,
        "metadata": {
            "timestamp": "2024-08-10T17:49:14Z",
            "tools": [
                {
                    "vendor": "box-project",
                    "name": "box",
                    "version": "4.6.2@29c3585"
                },
                {
                    "vendor": "bartlett",
                    "name": "box-manifest",
                    "version": "4.x-dev@3eff4eb"
                }
            ],
            "properties": [
                {
                    "name": "specVersion",
                    "value": "1.6"
                },
                {
                    "name": "bomFormat",
                    "value": "CycloneDX"
                }
            ]
        },
        "components": [
            {
                "bom-ref": "pkg:composer/psr/log@3.0.0",
                "type": "library",
                "name": "log",
                "version": "3.0.0",
                "group": "psr",
                "purl": "pkg:composer/psr/log@3.0.0"
            }
        ],
        "dependencies": [
            {
                "ref": "pkg:composer/psr/log@3.0.0"
            }
        ]
    }
    ```

## :material-numeric-2-box: With new pipeline command

=== "Command"

    ```shell
    box-manifest make -r sbom.json build
    ```

=== ":octicons-file-code-16: `sbom.json`"

    ```json
    {
        "$schema": "http://cyclonedx.org/schema/bom-1.6.schema.json",
        "bomFormat": "CycloneDX",
        "specVersion": "1.6",
        "serialNumber": "urn:uuid:bb11a687-9725-4c28-b8c0-1a15377274b5",
        "version": 1,
        "metadata": {
            "timestamp": "2024-08-10T17:49:14Z",
            "tools": [
                {
                    "vendor": "box-project",
                    "name": "box",
                    "version": "4.6.2@29c3585"
                },
                {
                    "vendor": "bartlett",
                    "name": "box-manifest",
                    "version": "4.x-dev@3eff4eb"
                }
            ],
            "properties": [
                {
                    "name": "specVersion",
                    "value": "1.6"
                },
                {
                    "name": "bomFormat",
                    "value": "CycloneDX"
                }
            ]
        },
        "components": [
            {
                "bom-ref": "pkg:composer/psr/log@3.0.0",
                "type": "library",
                "name": "log",
                "version": "3.0.0",
                "group": "psr",
                "purl": "pkg:composer/psr/log@3.0.0"
            }
        ],
        "dependencies": [
            {
                "ref": "pkg:composer/psr/log@3.0.0"
            }
        ]
    }
    ```
