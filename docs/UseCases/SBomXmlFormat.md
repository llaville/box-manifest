<!-- markdownlint-disable MD013 MD033 -->
# Manifest in `sbom` XML format

Run following command :

```shell
bin/box-manifest contrib:add-manifest --output-file sbom.xml -v
```

That will print following results :

<details>
<summary>standard output contents</summary>

```text
Box-Manifest version 3.x-dev for Box 4.3.7@e89dfe8

 // Loading the configuration file "/shared/backups/bartlett/box-manifest/box.json.dist".

<?xml version="1.0" encoding="UTF-8"?>
<bom xmlns="http://cyclonedx.org/schema/bom/1.3" version="1">
  <metadata>
    <tools>
      <tool>
        <vendor><![CDATA[box-project]]></vendor>
        <name><![CDATA[box]]></name>
        <version><![CDATA[4.3.7@e89dfe8]]></version>
      </tool>
    </tools>
    <component type="application" bom-ref="pkg:composer/bartlett/box-manifest@3.x-dev%4036c1394">
      <group><![CDATA[bartlett]]></group>
      <name><![CDATA[box-manifest]]></name>
      <version><![CDATA[3.x-dev@36c1394]]></version>
      <description><![CDATA[Create a manifest to a PHP Archive (PHAR) for the BOX project (https://github.com/box-project/box)]]></description>
      <licenses>
        <license>
          <id><![CDATA[MIT]]></id>
        </license>
      </licenses>
      <purl><![CDATA[pkg:composer/bartlett/box-manifest@3.x-dev%4036c1394]]></purl>
    </component>
  </metadata>
  <components>
    <component type="library" bom-ref="pkg:composer/amphp/amp@v2.6.2">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[amp]]></name>
      <version><![CDATA[v2.6.2]]></version>
      <purl><![CDATA[pkg:composer/amphp/amp@v2.6.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/byte-stream@v1.8.1">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[byte-stream]]></name>
      <version><![CDATA[v1.8.1]]></version>
      <purl><![CDATA[pkg:composer/amphp/byte-stream@v1.8.1]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/parallel@v1.4.2">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[parallel]]></name>
      <version><![CDATA[v1.4.2]]></version>
      <purl><![CDATA[pkg:composer/amphp/parallel@v1.4.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/parallel-functions@v1.1.0">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[parallel-functions]]></name>
      <version><![CDATA[v1.1.0]]></version>
      <purl><![CDATA[pkg:composer/amphp/parallel-functions@v1.1.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/parser@v1.1.0">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[parser]]></name>
      <version><![CDATA[v1.1.0]]></version>
      <purl><![CDATA[pkg:composer/amphp/parser@v1.1.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/process@v1.1.4">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[process]]></name>
      <version><![CDATA[v1.1.4]]></version>
      <purl><![CDATA[pkg:composer/amphp/process@v1.1.4]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/serialization@v1.0.0">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[serialization]]></name>
      <version><![CDATA[v1.0.0]]></version>
      <purl><![CDATA[pkg:composer/amphp/serialization@v1.0.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/amphp/sync@v1.4.2">
      <group><![CDATA[amphp]]></group>
      <name><![CDATA[sync]]></name>
      <version><![CDATA[v1.4.2]]></version>
      <purl><![CDATA[pkg:composer/amphp/sync@v1.4.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/ca-bundle@1.3.5">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[ca-bundle]]></name>
      <version><![CDATA[1.3.5]]></version>
      <purl><![CDATA[pkg:composer/composer/ca-bundle@1.3.5]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/class-map-generator@1.0.0">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[class-map-generator]]></name>
      <version><![CDATA[1.0.0]]></version>
      <purl><![CDATA[pkg:composer/composer/class-map-generator@1.0.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/composer@2.5.4">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[composer]]></name>
      <version><![CDATA[2.5.4]]></version>
      <purl><![CDATA[pkg:composer/composer/composer@2.5.4]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/metadata-minifier@1.0.0">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[metadata-minifier]]></name>
      <version><![CDATA[1.0.0]]></version>
      <purl><![CDATA[pkg:composer/composer/metadata-minifier@1.0.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/pcre@3.1.0">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[pcre]]></name>
      <version><![CDATA[3.1.0]]></version>
      <purl><![CDATA[pkg:composer/composer/pcre@3.1.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/semver@3.3.2">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[semver]]></name>
      <version><![CDATA[3.3.2]]></version>
      <purl><![CDATA[pkg:composer/composer/semver@3.3.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/spdx-licenses@1.5.7">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[spdx-licenses]]></name>
      <version><![CDATA[1.5.7]]></version>
      <purl><![CDATA[pkg:composer/composer/spdx-licenses@1.5.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/composer/xdebug-handler@3.0.3">
      <group><![CDATA[composer]]></group>
      <name><![CDATA[xdebug-handler]]></name>
      <version><![CDATA[3.0.3]]></version>
      <purl><![CDATA[pkg:composer/composer/xdebug-handler@3.0.3]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/cyclonedx/cyclonedx-library@v1.6.3">
      <group><![CDATA[cyclonedx]]></group>
      <name><![CDATA[cyclonedx-library]]></name>
      <version><![CDATA[v1.6.3]]></version>
      <purl><![CDATA[pkg:composer/cyclonedx/cyclonedx-library@v1.6.3]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/fidry/console@0.5.5">
      <group><![CDATA[fidry]]></group>
      <name><![CDATA[console]]></name>
      <version><![CDATA[0.5.5]]></version>
      <purl><![CDATA[pkg:composer/fidry/console@0.5.5]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/humbug/box@4.3.7">
      <group><![CDATA[humbug]]></group>
      <name><![CDATA[box]]></name>
      <version><![CDATA[4.3.7]]></version>
      <purl><![CDATA[pkg:composer/humbug/box@4.3.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/humbug/php-scoper@0.18.2">
      <group><![CDATA[humbug]]></group>
      <name><![CDATA[php-scoper]]></name>
      <version><![CDATA[0.18.2]]></version>
      <purl><![CDATA[pkg:composer/humbug/php-scoper@0.18.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/jetbrains/phpstorm-stubs@v2022.3">
      <group><![CDATA[jetbrains]]></group>
      <name><![CDATA[phpstorm-stubs]]></name>
      <version><![CDATA[v2022.3]]></version>
      <purl><![CDATA[pkg:composer/jetbrains/phpstorm-stubs@v2022.3]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/justinrainbow/json-schema@5.2.12">
      <group><![CDATA[justinrainbow]]></group>
      <name><![CDATA[json-schema]]></name>
      <version><![CDATA[5.2.12]]></version>
      <purl><![CDATA[pkg:composer/justinrainbow/json-schema@5.2.12]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/laravel/serializable-closure@v1.3.0">
      <group><![CDATA[laravel]]></group>
      <name><![CDATA[serializable-closure]]></name>
      <version><![CDATA[v1.3.0]]></version>
      <purl><![CDATA[pkg:composer/laravel/serializable-closure@v1.3.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/nikic/iter@v2.2.0">
      <group><![CDATA[nikic]]></group>
      <name><![CDATA[iter]]></name>
      <version><![CDATA[v2.2.0]]></version>
      <purl><![CDATA[pkg:composer/nikic/iter@v2.2.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/nikic/php-parser@v4.15.4">
      <group><![CDATA[nikic]]></group>
      <name><![CDATA[php-parser]]></name>
      <version><![CDATA[v4.15.4]]></version>
      <purl><![CDATA[pkg:composer/nikic/php-parser@v4.15.4]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/package-url/packageurl-php@1.0.5">
      <group><![CDATA[package-url]]></group>
      <name><![CDATA[packageurl-php]]></name>
      <version><![CDATA[1.0.5]]></version>
      <purl><![CDATA[pkg:composer/package-url/packageurl-php@1.0.5]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/paragonie/constant_time_encoding@v2.6.3">
      <group><![CDATA[paragonie]]></group>
      <name><![CDATA[constant_time_encoding]]></name>
      <version><![CDATA[v2.6.3]]></version>
      <purl><![CDATA[pkg:composer/paragonie/constant_time_encoding@v2.6.3]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/paragonie/pharaoh@v0.6.0">
      <group><![CDATA[paragonie]]></group>
      <name><![CDATA[pharaoh]]></name>
      <version><![CDATA[v0.6.0]]></version>
      <purl><![CDATA[pkg:composer/paragonie/pharaoh@v0.6.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/phpdocumentor/reflection-common@2.2.0">
      <group><![CDATA[phpdocumentor]]></group>
      <name><![CDATA[reflection-common]]></name>
      <version><![CDATA[2.2.0]]></version>
      <purl><![CDATA[pkg:composer/phpdocumentor/reflection-common@2.2.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/phpdocumentor/reflection-docblock@5.3.0">
      <group><![CDATA[phpdocumentor]]></group>
      <name><![CDATA[reflection-docblock]]></name>
      <version><![CDATA[5.3.0]]></version>
      <purl><![CDATA[pkg:composer/phpdocumentor/reflection-docblock@5.3.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/phpdocumentor/type-resolver@1.6.2">
      <group><![CDATA[phpdocumentor]]></group>
      <name><![CDATA[type-resolver]]></name>
      <version><![CDATA[1.6.2]]></version>
      <purl><![CDATA[pkg:composer/phpdocumentor/type-resolver@1.6.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/phplang/scope-exit@1.0.0">
      <group><![CDATA[phplang]]></group>
      <name><![CDATA[scope-exit]]></name>
      <version><![CDATA[1.0.0]]></version>
      <purl><![CDATA[pkg:composer/phplang/scope-exit@1.0.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/psr/container@2.0.2">
      <group><![CDATA[psr]]></group>
      <name><![CDATA[container]]></name>
      <version><![CDATA[2.0.2]]></version>
      <purl><![CDATA[pkg:composer/psr/container@2.0.2]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/psr/event-dispatcher@1.0.0">
      <group><![CDATA[psr]]></group>
      <name><![CDATA[event-dispatcher]]></name>
      <version><![CDATA[1.0.0]]></version>
      <purl><![CDATA[pkg:composer/psr/event-dispatcher@1.0.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/psr/log@3.0.0">
      <group><![CDATA[psr]]></group>
      <name><![CDATA[log]]></name>
      <version><![CDATA[3.0.0]]></version>
      <purl><![CDATA[pkg:composer/psr/log@3.0.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/react/promise@v2.9.0">
      <group><![CDATA[react]]></group>
      <name><![CDATA[promise]]></name>
      <version><![CDATA[v2.9.0]]></version>
      <purl><![CDATA[pkg:composer/react/promise@v2.9.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/seld/jsonlint@1.9.0">
      <group><![CDATA[seld]]></group>
      <name><![CDATA[jsonlint]]></name>
      <version><![CDATA[1.9.0]]></version>
      <purl><![CDATA[pkg:composer/seld/jsonlint@1.9.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/seld/phar-utils@1.2.1">
      <group><![CDATA[seld]]></group>
      <name><![CDATA[phar-utils]]></name>
      <version><![CDATA[1.2.1]]></version>
      <purl><![CDATA[pkg:composer/seld/phar-utils@1.2.1]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/seld/signal-handler@2.0.1">
      <group><![CDATA[seld]]></group>
      <name><![CDATA[signal-handler]]></name>
      <version><![CDATA[2.0.1]]></version>
      <purl><![CDATA[pkg:composer/seld/signal-handler@2.0.1]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/swaggest/json-diff@v3.10.4">
      <group><![CDATA[swaggest]]></group>
      <name><![CDATA[json-diff]]></name>
      <version><![CDATA[v3.10.4]]></version>
      <purl><![CDATA[pkg:composer/swaggest/json-diff@v3.10.4]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/swaggest/json-schema@v0.12.41">
      <group><![CDATA[swaggest]]></group>
      <name><![CDATA[json-schema]]></name>
      <version><![CDATA[v0.12.41]]></version>
      <purl><![CDATA[pkg:composer/swaggest/json-schema@v0.12.41]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/console@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[console]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/console@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/deprecation-contracts@v3.2.1">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[deprecation-contracts]]></name>
      <version><![CDATA[v3.2.1]]></version>
      <purl><![CDATA[pkg:composer/symfony/deprecation-contracts@v3.2.1]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/event-dispatcher-contracts@v3.2.1">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[event-dispatcher-contracts]]></name>
      <version><![CDATA[v3.2.1]]></version>
      <purl><![CDATA[pkg:composer/symfony/event-dispatcher-contracts@v3.2.1]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/filesystem@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[filesystem]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/filesystem@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/finder@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[finder]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/finder@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/polyfill-ctype@v1.27.0">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[polyfill-ctype]]></name>
      <version><![CDATA[v1.27.0]]></version>
      <purl><![CDATA[pkg:composer/symfony/polyfill-ctype@v1.27.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/polyfill-intl-grapheme@v1.27.0">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[polyfill-intl-grapheme]]></name>
      <version><![CDATA[v1.27.0]]></version>
      <purl><![CDATA[pkg:composer/symfony/polyfill-intl-grapheme@v1.27.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/polyfill-intl-normalizer@v1.27.0">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[polyfill-intl-normalizer]]></name>
      <version><![CDATA[v1.27.0]]></version>
      <purl><![CDATA[pkg:composer/symfony/polyfill-intl-normalizer@v1.27.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/polyfill-mbstring@v1.27.0">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[polyfill-mbstring]]></name>
      <version><![CDATA[v1.27.0]]></version>
      <purl><![CDATA[pkg:composer/symfony/polyfill-mbstring@v1.27.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/polyfill-php73@v1.27.0">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[polyfill-php73]]></name>
      <version><![CDATA[v1.27.0]]></version>
      <purl><![CDATA[pkg:composer/symfony/polyfill-php73@v1.27.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/process@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[process]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/process@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/serializer@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[serializer]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/serializer@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/service-contracts@v3.2.1">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[service-contracts]]></name>
      <version><![CDATA[v3.2.1]]></version>
      <purl><![CDATA[pkg:composer/symfony/service-contracts@v3.2.1]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/string@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[string]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/string@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/symfony/var-dumper@v6.2.7">
      <group><![CDATA[symfony]]></group>
      <name><![CDATA[var-dumper]]></name>
      <version><![CDATA[v6.2.7]]></version>
      <purl><![CDATA[pkg:composer/symfony/var-dumper@v6.2.7]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/thecodingmachine/safe@v2.4.0">
      <group><![CDATA[thecodingmachine]]></group>
      <name><![CDATA[safe]]></name>
      <version><![CDATA[v2.4.0]]></version>
      <purl><![CDATA[pkg:composer/thecodingmachine/safe@v2.4.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/ulrichsg/getopt-php@v3.4.0">
      <group><![CDATA[ulrichsg]]></group>
      <name><![CDATA[getopt-php]]></name>
      <version><![CDATA[v3.4.0]]></version>
      <purl><![CDATA[pkg:composer/ulrichsg/getopt-php@v3.4.0]]></purl>
    </component>
    <component type="library" bom-ref="pkg:composer/webmozart/assert@1.11.0">
      <group><![CDATA[webmozart]]></group>
      <name><![CDATA[assert]]></name>
      <version><![CDATA[1.11.0]]></version>
      <purl><![CDATA[pkg:composer/webmozart/assert@1.11.0]]></purl>
    </component>
  </components>
  <dependencies>
    <dependency ref="pkg:composer/amphp/amp@v2.6.2"/>
    <dependency ref="pkg:composer/amphp/byte-stream@v1.8.1"/>
    <dependency ref="pkg:composer/amphp/parallel@v1.4.2"/>
    <dependency ref="pkg:composer/amphp/parallel-functions@v1.1.0"/>
    <dependency ref="pkg:composer/amphp/parser@v1.1.0"/>
    <dependency ref="pkg:composer/amphp/process@v1.1.4"/>
    <dependency ref="pkg:composer/amphp/serialization@v1.0.0"/>
    <dependency ref="pkg:composer/amphp/sync@v1.4.2"/>
    <dependency ref="pkg:composer/composer/ca-bundle@1.3.5"/>
    <dependency ref="pkg:composer/composer/class-map-generator@1.0.0"/>
    <dependency ref="pkg:composer/composer/composer@2.5.4"/>
    <dependency ref="pkg:composer/composer/metadata-minifier@1.0.0"/>
    <dependency ref="pkg:composer/composer/pcre@3.1.0"/>
    <dependency ref="pkg:composer/composer/semver@3.3.2"/>
    <dependency ref="pkg:composer/composer/spdx-licenses@1.5.7"/>
    <dependency ref="pkg:composer/composer/xdebug-handler@3.0.3"/>
    <dependency ref="pkg:composer/cyclonedx/cyclonedx-library@v1.6.3"/>
    <dependency ref="pkg:composer/fidry/console@0.5.5"/>
    <dependency ref="pkg:composer/humbug/box@4.3.7"/>
    <dependency ref="pkg:composer/humbug/php-scoper@0.18.2"/>
    <dependency ref="pkg:composer/jetbrains/phpstorm-stubs@v2022.3"/>
    <dependency ref="pkg:composer/justinrainbow/json-schema@5.2.12"/>
    <dependency ref="pkg:composer/laravel/serializable-closure@v1.3.0"/>
    <dependency ref="pkg:composer/nikic/iter@v2.2.0"/>
    <dependency ref="pkg:composer/nikic/php-parser@v4.15.4"/>
    <dependency ref="pkg:composer/package-url/packageurl-php@1.0.5"/>
    <dependency ref="pkg:composer/paragonie/constant_time_encoding@v2.6.3"/>
    <dependency ref="pkg:composer/paragonie/pharaoh@v0.6.0"/>
    <dependency ref="pkg:composer/phpdocumentor/reflection-common@2.2.0"/>
    <dependency ref="pkg:composer/phpdocumentor/reflection-docblock@5.3.0"/>
    <dependency ref="pkg:composer/phpdocumentor/type-resolver@1.6.2"/>
    <dependency ref="pkg:composer/phplang/scope-exit@1.0.0"/>
    <dependency ref="pkg:composer/psr/container@2.0.2"/>
    <dependency ref="pkg:composer/psr/event-dispatcher@1.0.0"/>
    <dependency ref="pkg:composer/psr/log@3.0.0"/>
    <dependency ref="pkg:composer/react/promise@v2.9.0"/>
    <dependency ref="pkg:composer/seld/jsonlint@1.9.0"/>
    <dependency ref="pkg:composer/seld/phar-utils@1.2.1"/>
    <dependency ref="pkg:composer/seld/signal-handler@2.0.1"/>
    <dependency ref="pkg:composer/swaggest/json-diff@v3.10.4"/>
    <dependency ref="pkg:composer/swaggest/json-schema@v0.12.41"/>
    <dependency ref="pkg:composer/symfony/console@v6.2.7"/>
    <dependency ref="pkg:composer/symfony/deprecation-contracts@v3.2.1"/>
    <dependency ref="pkg:composer/symfony/event-dispatcher-contracts@v3.2.1"/>
    <dependency ref="pkg:composer/symfony/filesystem@v6.2.7"/>
    <dependency ref="pkg:composer/symfony/finder@v6.2.7"/>
    <dependency ref="pkg:composer/symfony/polyfill-ctype@v1.27.0"/>
    <dependency ref="pkg:composer/symfony/polyfill-intl-grapheme@v1.27.0"/>
    <dependency ref="pkg:composer/symfony/polyfill-intl-normalizer@v1.27.0"/>
    <dependency ref="pkg:composer/symfony/polyfill-mbstring@v1.27.0"/>
    <dependency ref="pkg:composer/symfony/polyfill-php73@v1.27.0"/>
    <dependency ref="pkg:composer/symfony/process@v6.2.7"/>
    <dependency ref="pkg:composer/symfony/serializer@v6.2.7"/>
    <dependency ref="pkg:composer/symfony/service-contracts@v3.2.1"/>
    <dependency ref="pkg:composer/symfony/string@v6.2.7"/>
    <dependency ref="pkg:composer/symfony/var-dumper@v6.2.7"/>
    <dependency ref="pkg:composer/thecodingmachine/safe@v2.4.0"/>
    <dependency ref="pkg:composer/ulrichsg/getopt-php@v3.4.0"/>
    <dependency ref="pkg:composer/webmozart/assert@1.11.0"/>
    <dependency ref="pkg:composer/bartlett/box-manifest@3.x-dev%4036c1394"/>
  </dependencies>
</bom>

 // Writing results to standard output
```

</details>
