<!-- markdownlint-disable MD013 MD029 MD033 -->
# Generate manifest files

In this tutorial we will build a manifest in two formats (SBOM XML and ANSI text).

## :material-numeric-1-box: Generate a SBOM XML manifest

First we will build a SBOM XML version following default ([CycloneDX][cyclonedx]) [specification][cyclonedx-spec] 1.6

=== "Command"

    ```shell
    box-manifest make -r sbom.xml build
    ```

=== ":octicons-file-code-16: sbom.xml"

    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <bom xmlns="http://cyclonedx.org/schema/bom/1.6" version="1" serialNumber="urn:uuid:ad64fd89-99e9-4976-b9d2-6ec4e88b4b15">
      <metadata>
        <timestamp><![CDATA[2024-08-07T04:23:01Z]]></timestamp>
        <tools>
          <tool>
            <vendor><![CDATA[box-project]]></vendor>
            <name><![CDATA[box]]></name>
            <version><![CDATA[4.6.2@29c3585]]></version>
          </tool>
          <tool>
            <vendor><![CDATA[bartlett]]></vendor>
            <name><![CDATA[box-manifest]]></name>
            <version><![CDATA[4.x-dev@3eff4eb]]></version>
          </tool>
        </tools>
        <properties>
          <property name="specVersion"><![CDATA[1.6]]></property>
          <property name="bomFormat"><![CDATA[CycloneDX]]></property>
        </properties>
      </metadata>
      <components>
        <component type="library" bom-ref="pkg:composer/psr/log@3.0.0">
          <group><![CDATA[psr]]></group>
          <name><![CDATA[log]]></name>
          <version><![CDATA[3.0.0]]></version>
          <purl><![CDATA[pkg:composer/psr/log@3.0.0]]></purl>
        </component>
      </components>
      <dependencies>
        <dependency ref="pkg:composer/psr/log@3.0.0"/>
      </dependencies>
    </bom>
    ```

## :material-numeric-2-box: Generate a decorated TEXT manifest

Next we will build a decorated TEXT version

=== "Command"

    ```shell
    box-manifest make -r ansi.txt build
    ```

=== ":octicons-file-code-16: ansi.txt"

    ```text
    root/app-fixtures: 3.x-dev@9661882
     requires php ^8.1: 8.2.21
     requires ext-phar *: 8.2.21
     requires (for development) psr/log ^3.0: 3.0.0
    ```

=== "Output (preview)"

    ![ansi format](../assets/images/app-fixtures-ansi.png)

## :material-numeric-3-box: Build your PHP Archive

Now its turn to declare these files to the BOX config file, with :

```json
{
    "files-bin": ["sbom.xml", "ansi.txt"]
}
```

In [Part 3](./generate-box-json-dist.md) of the tutorial, we will see how to dynamically add it without introduced errors.

Then finally, compile your PHP Archive with `box compile` command,
the metadata contents is only used as fallback contents in case you forgot to declare `files-bin` entries.

=== "Command"

    ```shell
    vendor/bin/box compile --config app-fixtures.box.json.dist
    ```

=== "Output"

    ```text
        ____
       / __ )____  _  __
      / __  / __ \| |/_/
     / /_/ / /_/ />  <
    /_____/\____/_/|_|


    Box version 4.6.2@29c3585

     // Loading the configuration file "app-fixtures.box.json.dist".

    🔨  Building the PHAR "/shared/backups/bartlett/box-manifest/examples/app-fixtures/app-fixtures.phar"

    ? Checking Composer compatibility
        > Supported version detected
    ? No compactor to register
    ? Adding main file: /shared/backups/bartlett/box-manifest/examples/app-fixtures/index.php
    ? Adding requirements checker
    ? Adding binary files
        > 36 file(s)
    ? Auto-discover files? No
    ? Exclude dev files? Yes
    ? Adding files
        > 25 file(s)
    ? Using stub file: /shared/backups/bartlett/box-manifest/examples/app-fixtures/app-fixtures-stub.php
    ? Dumping the Composer autoloader
    ? Removing the Composer dump artefacts
    ? Compressing with the algorithm "GZ"
        > Warning: the extension "zlib" will now be required to execute the PHAR
    ? Setting file permissions to 0755
    * Done.

    No recommendation found.
    ⚠️  1 warning found:
        - The "alias" setting has been set but is ignored since a custom stub path is used

     // PHAR: 60 files (48.53KB)
     // You can inspect the generated PHAR with the "info" command.

     // Memory usage: 12.85MB (peak: 13.30MB), time: <1sec

    ```

[cyclonedx]: https://cyclonedx.org/
[cyclonedx-spec]: https://cyclonedx.org/specification/overview/
