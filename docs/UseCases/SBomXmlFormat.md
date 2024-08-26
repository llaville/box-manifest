<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `sbom` XML format

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
    box-manifest manifest:build -f sbom-xml
    ```

=== "Output"

    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <bom xmlns="http://cyclonedx.org/schema/bom/1.6" version="1" serialNumber="urn:uuid:1c9c48eb-ab0f-4f57-96d5-d6640ec8cbf6">
      <metadata>
        <timestamp><![CDATA[2024-08-10T17:52:54Z]]></timestamp>
        <tools>
          <tool>
            <vendor><![CDATA[box-project]]></vendor>
            <name><![CDATA[box]]></name>
            <version><![CDATA[4.6.2@29c3585]]></version>
          </tool>
          <tool>
            <vendor><![CDATA[bartlett]]></vendor>
            <name><![CDATA[box-manifest]]></name>
            <version><![CDATA[4.0.0]]></version>
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

## :material-numeric-2-box: With new pipeline command

=== "Command"

    ```shell
    box-manifest make -r sbom.xml build
    ```

=== ":octicons-file-code-16: `sbom.xml`"

    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <bom xmlns="http://cyclonedx.org/schema/bom/1.6" version="1" serialNumber="urn:uuid:1c9c48eb-ab0f-4f57-96d5-d6640ec8cbf6">
      <metadata>
        <timestamp><![CDATA[2024-08-10T17:52:54Z]]></timestamp>
        <tools>
          <tool>
            <vendor><![CDATA[box-project]]></vendor>
            <name><![CDATA[box]]></name>
            <version><![CDATA[4.6.2@29c3585]]></version>
          </tool>
          <tool>
            <vendor><![CDATA[bartlett]]></vendor>
            <name><![CDATA[box-manifest]]></name>
            <version><![CDATA[4.0.0]]></version>
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
