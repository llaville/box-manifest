<!-- markdownlint-disable MD013 -->
# Manifest in `sbom` JSON format

Run either following commands :

```shell
bin/box-manifest contrib:add-manifest --output-file sbom.json -v
```

Or even

```shell
bin/box-manifest contrib:add-manifest --format sbom
```

That will print following results :

<details>
<summary>standard output contents</summary>

```text
Box-Manifest version 3.x-dev for Box 4.3.7@e89dfe8

 // Loading the configuration file "/shared/backups/bartlett/box-manifest/box.json.dist".

{
    "$schema": "http://cyclonedx.org/schema/bom-1.3.schema.json",
    "bomFormat": "CycloneDX",
    "specVersion": "1.3",
    "version": 1,
    "metadata": {
        "tools": [
            {
                "vendor": "box-project",
                "name": "box",
                "version": "4.3.7@e89dfe8"
            }
        ],
        "component": {
            "bom-ref": "pkg:composer/bartlett/box-manifest@3.x-dev%4036c1394",
            "type": "application",
            "name": "box-manifest",
            "version": "3.x-dev@36c1394",
            "group": "bartlett",
            "description": "Create a manifest to a PHP Archive (PHAR) for the BOX project (https://github.com/box-project/box)",
            "licenses": [
                {
                    "license": {
                        "id": "MIT"
                    }
                }
            ],
            "purl": "pkg:composer/bartlett/box-manifest@3.x-dev%4036c1394"
        }
    },
    "components": [
        {
            "bom-ref": "pkg:composer/amphp/amp@v2.6.2",
            "type": "library",
            "name": "amp",
            "version": "v2.6.2",
            "group": "amphp",
            "purl": "pkg:composer/amphp/amp@v2.6.2"
        },
        {
            "bom-ref": "pkg:composer/amphp/byte-stream@v1.8.1",
            "type": "library",
            "name": "byte-stream",
            "version": "v1.8.1",
            "group": "amphp",
            "purl": "pkg:composer/amphp/byte-stream@v1.8.1"
        },
        {
            "bom-ref": "pkg:composer/amphp/parallel@v1.4.2",
            "type": "library",
            "name": "parallel",
            "version": "v1.4.2",
            "group": "amphp",
            "purl": "pkg:composer/amphp/parallel@v1.4.2"
        },
        {
            "bom-ref": "pkg:composer/amphp/parallel-functions@v1.1.0",
            "type": "library",
            "name": "parallel-functions",
            "version": "v1.1.0",
            "group": "amphp",
            "purl": "pkg:composer/amphp/parallel-functions@v1.1.0"
        },
        {
            "bom-ref": "pkg:composer/amphp/parser@v1.1.0",
            "type": "library",
            "name": "parser",
            "version": "v1.1.0",
            "group": "amphp",
            "purl": "pkg:composer/amphp/parser@v1.1.0"
        },
        {
            "bom-ref": "pkg:composer/amphp/process@v1.1.4",
            "type": "library",
            "name": "process",
            "version": "v1.1.4",
            "group": "amphp",
            "purl": "pkg:composer/amphp/process@v1.1.4"
        },
        {
            "bom-ref": "pkg:composer/amphp/serialization@v1.0.0",
            "type": "library",
            "name": "serialization",
            "version": "v1.0.0",
            "group": "amphp",
            "purl": "pkg:composer/amphp/serialization@v1.0.0"
        },
        {
            "bom-ref": "pkg:composer/amphp/sync@v1.4.2",
            "type": "library",
            "name": "sync",
            "version": "v1.4.2",
            "group": "amphp",
            "purl": "pkg:composer/amphp/sync@v1.4.2"
        },
        {
            "bom-ref": "pkg:composer/composer/ca-bundle@1.3.5",
            "type": "library",
            "name": "ca-bundle",
            "version": "1.3.5",
            "group": "composer",
            "purl": "pkg:composer/composer/ca-bundle@1.3.5"
        },
        {
            "bom-ref": "pkg:composer/composer/class-map-generator@1.0.0",
            "type": "library",
            "name": "class-map-generator",
            "version": "1.0.0",
            "group": "composer",
            "purl": "pkg:composer/composer/class-map-generator@1.0.0"
        },
        {
            "bom-ref": "pkg:composer/composer/composer@2.5.4",
            "type": "library",
            "name": "composer",
            "version": "2.5.4",
            "group": "composer",
            "purl": "pkg:composer/composer/composer@2.5.4"
        },
        {
            "bom-ref": "pkg:composer/composer/metadata-minifier@1.0.0",
            "type": "library",
            "name": "metadata-minifier",
            "version": "1.0.0",
            "group": "composer",
            "purl": "pkg:composer/composer/metadata-minifier@1.0.0"
        },
        {
            "bom-ref": "pkg:composer/composer/pcre@3.1.0",
            "type": "library",
            "name": "pcre",
            "version": "3.1.0",
            "group": "composer",
            "purl": "pkg:composer/composer/pcre@3.1.0"
        },
        {
            "bom-ref": "pkg:composer/composer/semver@3.3.2",
            "type": "library",
            "name": "semver",
            "version": "3.3.2",
            "group": "composer",
            "purl": "pkg:composer/composer/semver@3.3.2"
        },
        {
            "bom-ref": "pkg:composer/composer/spdx-licenses@1.5.7",
            "type": "library",
            "name": "spdx-licenses",
            "version": "1.5.7",
            "group": "composer",
            "purl": "pkg:composer/composer/spdx-licenses@1.5.7"
        },
        {
            "bom-ref": "pkg:composer/composer/xdebug-handler@3.0.3",
            "type": "library",
            "name": "xdebug-handler",
            "version": "3.0.3",
            "group": "composer",
            "purl": "pkg:composer/composer/xdebug-handler@3.0.3"
        },
        {
            "bom-ref": "pkg:composer/cyclonedx/cyclonedx-library@v1.6.3",
            "type": "library",
            "name": "cyclonedx-library",
            "version": "v1.6.3",
            "group": "cyclonedx",
            "purl": "pkg:composer/cyclonedx/cyclonedx-library@v1.6.3"
        },
        {
            "bom-ref": "pkg:composer/fidry/console@0.5.5",
            "type": "library",
            "name": "console",
            "version": "0.5.5",
            "group": "fidry",
            "purl": "pkg:composer/fidry/console@0.5.5"
        },
        {
            "bom-ref": "pkg:composer/humbug/box@4.3.7",
            "type": "library",
            "name": "box",
            "version": "4.3.7",
            "group": "humbug",
            "purl": "pkg:composer/humbug/box@4.3.7"
        },
        {
            "bom-ref": "pkg:composer/humbug/php-scoper@0.18.2",
            "type": "library",
            "name": "php-scoper",
            "version": "0.18.2",
            "group": "humbug",
            "purl": "pkg:composer/humbug/php-scoper@0.18.2"
        },
        {
            "bom-ref": "pkg:composer/jetbrains/phpstorm-stubs@v2022.3",
            "type": "library",
            "name": "phpstorm-stubs",
            "version": "v2022.3",
            "group": "jetbrains",
            "purl": "pkg:composer/jetbrains/phpstorm-stubs@v2022.3"
        },
        {
            "bom-ref": "pkg:composer/justinrainbow/json-schema@5.2.12",
            "type": "library",
            "name": "json-schema",
            "version": "5.2.12",
            "group": "justinrainbow",
            "purl": "pkg:composer/justinrainbow/json-schema@5.2.12"
        },
        {
            "bom-ref": "pkg:composer/laravel/serializable-closure@v1.3.0",
            "type": "library",
            "name": "serializable-closure",
            "version": "v1.3.0",
            "group": "laravel",
            "purl": "pkg:composer/laravel/serializable-closure@v1.3.0"
        },
        {
            "bom-ref": "pkg:composer/nikic/iter@v2.2.0",
            "type": "library",
            "name": "iter",
            "version": "v2.2.0",
            "group": "nikic",
            "purl": "pkg:composer/nikic/iter@v2.2.0"
        },
        {
            "bom-ref": "pkg:composer/nikic/php-parser@v4.15.4",
            "type": "library",
            "name": "php-parser",
            "version": "v4.15.4",
            "group": "nikic",
            "purl": "pkg:composer/nikic/php-parser@v4.15.4"
        },
        {
            "bom-ref": "pkg:composer/package-url/packageurl-php@1.0.5",
            "type": "library",
            "name": "packageurl-php",
            "version": "1.0.5",
            "group": "package-url",
            "purl": "pkg:composer/package-url/packageurl-php@1.0.5"
        },
        {
            "bom-ref": "pkg:composer/paragonie/constant_time_encoding@v2.6.3",
            "type": "library",
            "name": "constant_time_encoding",
            "version": "v2.6.3",
            "group": "paragonie",
            "purl": "pkg:composer/paragonie/constant_time_encoding@v2.6.3"
        },
        {
            "bom-ref": "pkg:composer/paragonie/pharaoh@v0.6.0",
            "type": "library",
            "name": "pharaoh",
            "version": "v0.6.0",
            "group": "paragonie",
            "purl": "pkg:composer/paragonie/pharaoh@v0.6.0"
        },
        {
            "bom-ref": "pkg:composer/phpdocumentor/reflection-common@2.2.0",
            "type": "library",
            "name": "reflection-common",
            "version": "2.2.0",
            "group": "phpdocumentor",
            "purl": "pkg:composer/phpdocumentor/reflection-common@2.2.0"
        },
        {
            "bom-ref": "pkg:composer/phpdocumentor/reflection-docblock@5.3.0",
            "type": "library",
            "name": "reflection-docblock",
            "version": "5.3.0",
            "group": "phpdocumentor",
            "purl": "pkg:composer/phpdocumentor/reflection-docblock@5.3.0"
        },
        {
            "bom-ref": "pkg:composer/phpdocumentor/type-resolver@1.6.2",
            "type": "library",
            "name": "type-resolver",
            "version": "1.6.2",
            "group": "phpdocumentor",
            "purl": "pkg:composer/phpdocumentor/type-resolver@1.6.2"
        },
        {
            "bom-ref": "pkg:composer/phplang/scope-exit@1.0.0",
            "type": "library",
            "name": "scope-exit",
            "version": "1.0.0",
            "group": "phplang",
            "purl": "pkg:composer/phplang/scope-exit@1.0.0"
        },
        {
            "bom-ref": "pkg:composer/psr/container@2.0.2",
            "type": "library",
            "name": "container",
            "version": "2.0.2",
            "group": "psr",
            "purl": "pkg:composer/psr/container@2.0.2"
        },
        {
            "bom-ref": "pkg:composer/psr/event-dispatcher@1.0.0",
            "type": "library",
            "name": "event-dispatcher",
            "version": "1.0.0",
            "group": "psr",
            "purl": "pkg:composer/psr/event-dispatcher@1.0.0"
        },
        {
            "bom-ref": "pkg:composer/psr/log@3.0.0",
            "type": "library",
            "name": "log",
            "version": "3.0.0",
            "group": "psr",
            "purl": "pkg:composer/psr/log@3.0.0"
        },
        {
            "bom-ref": "pkg:composer/react/promise@v2.9.0",
            "type": "library",
            "name": "promise",
            "version": "v2.9.0",
            "group": "react",
            "purl": "pkg:composer/react/promise@v2.9.0"
        },
        {
            "bom-ref": "pkg:composer/seld/jsonlint@1.9.0",
            "type": "library",
            "name": "jsonlint",
            "version": "1.9.0",
            "group": "seld",
            "purl": "pkg:composer/seld/jsonlint@1.9.0"
        },
        {
            "bom-ref": "pkg:composer/seld/phar-utils@1.2.1",
            "type": "library",
            "name": "phar-utils",
            "version": "1.2.1",
            "group": "seld",
            "purl": "pkg:composer/seld/phar-utils@1.2.1"
        },
        {
            "bom-ref": "pkg:composer/seld/signal-handler@2.0.1",
            "type": "library",
            "name": "signal-handler",
            "version": "2.0.1",
            "group": "seld",
            "purl": "pkg:composer/seld/signal-handler@2.0.1"
        },
        {
            "bom-ref": "pkg:composer/swaggest/json-diff@v3.10.4",
            "type": "library",
            "name": "json-diff",
            "version": "v3.10.4",
            "group": "swaggest",
            "purl": "pkg:composer/swaggest/json-diff@v3.10.4"
        },
        {
            "bom-ref": "pkg:composer/swaggest/json-schema@v0.12.41",
            "type": "library",
            "name": "json-schema",
            "version": "v0.12.41",
            "group": "swaggest",
            "purl": "pkg:composer/swaggest/json-schema@v0.12.41"
        },
        {
            "bom-ref": "pkg:composer/symfony/console@v6.2.7",
            "type": "library",
            "name": "console",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/console@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/symfony/deprecation-contracts@v3.2.1",
            "type": "library",
            "name": "deprecation-contracts",
            "version": "v3.2.1",
            "group": "symfony",
            "purl": "pkg:composer/symfony/deprecation-contracts@v3.2.1"
        },
        {
            "bom-ref": "pkg:composer/symfony/event-dispatcher-contracts@v3.2.1",
            "type": "library",
            "name": "event-dispatcher-contracts",
            "version": "v3.2.1",
            "group": "symfony",
            "purl": "pkg:composer/symfony/event-dispatcher-contracts@v3.2.1"
        },
        {
            "bom-ref": "pkg:composer/symfony/filesystem@v6.2.7",
            "type": "library",
            "name": "filesystem",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/filesystem@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/symfony/finder@v6.2.7",
            "type": "library",
            "name": "finder",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/finder@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/symfony/polyfill-ctype@v1.27.0",
            "type": "library",
            "name": "polyfill-ctype",
            "version": "v1.27.0",
            "group": "symfony",
            "purl": "pkg:composer/symfony/polyfill-ctype@v1.27.0"
        },
        {
            "bom-ref": "pkg:composer/symfony/polyfill-intl-grapheme@v1.27.0",
            "type": "library",
            "name": "polyfill-intl-grapheme",
            "version": "v1.27.0",
            "group": "symfony",
            "purl": "pkg:composer/symfony/polyfill-intl-grapheme@v1.27.0"
        },
        {
            "bom-ref": "pkg:composer/symfony/polyfill-intl-normalizer@v1.27.0",
            "type": "library",
            "name": "polyfill-intl-normalizer",
            "version": "v1.27.0",
            "group": "symfony",
            "purl": "pkg:composer/symfony/polyfill-intl-normalizer@v1.27.0"
        },
        {
            "bom-ref": "pkg:composer/symfony/polyfill-mbstring@v1.27.0",
            "type": "library",
            "name": "polyfill-mbstring",
            "version": "v1.27.0",
            "group": "symfony",
            "purl": "pkg:composer/symfony/polyfill-mbstring@v1.27.0"
        },
        {
            "bom-ref": "pkg:composer/symfony/polyfill-php73@v1.27.0",
            "type": "library",
            "name": "polyfill-php73",
            "version": "v1.27.0",
            "group": "symfony",
            "purl": "pkg:composer/symfony/polyfill-php73@v1.27.0"
        },
        {
            "bom-ref": "pkg:composer/symfony/process@v6.2.7",
            "type": "library",
            "name": "process",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/process@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/symfony/serializer@v6.2.7",
            "type": "library",
            "name": "serializer",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/serializer@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/symfony/service-contracts@v3.2.1",
            "type": "library",
            "name": "service-contracts",
            "version": "v3.2.1",
            "group": "symfony",
            "purl": "pkg:composer/symfony/service-contracts@v3.2.1"
        },
        {
            "bom-ref": "pkg:composer/symfony/string@v6.2.7",
            "type": "library",
            "name": "string",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/string@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/symfony/var-dumper@v6.2.7",
            "type": "library",
            "name": "var-dumper",
            "version": "v6.2.7",
            "group": "symfony",
            "purl": "pkg:composer/symfony/var-dumper@v6.2.7"
        },
        {
            "bom-ref": "pkg:composer/thecodingmachine/safe@v2.4.0",
            "type": "library",
            "name": "safe",
            "version": "v2.4.0",
            "group": "thecodingmachine",
            "purl": "pkg:composer/thecodingmachine/safe@v2.4.0"
        },
        {
            "bom-ref": "pkg:composer/ulrichsg/getopt-php@v3.4.0",
            "type": "library",
            "name": "getopt-php",
            "version": "v3.4.0",
            "group": "ulrichsg",
            "purl": "pkg:composer/ulrichsg/getopt-php@v3.4.0"
        },
        {
            "bom-ref": "pkg:composer/webmozart/assert@1.11.0",
            "type": "library",
            "name": "assert",
            "version": "1.11.0",
            "group": "webmozart",
            "purl": "pkg:composer/webmozart/assert@1.11.0"
        }
    ],
    "dependencies": [
        {
            "ref": "pkg:composer/amphp/amp@v2.6.2"
        },
        {
            "ref": "pkg:composer/amphp/byte-stream@v1.8.1"
        },
        {
            "ref": "pkg:composer/amphp/parallel@v1.4.2"
        },
        {
            "ref": "pkg:composer/amphp/parallel-functions@v1.1.0"
        },
        {
            "ref": "pkg:composer/amphp/parser@v1.1.0"
        },
        {
            "ref": "pkg:composer/amphp/process@v1.1.4"
        },
        {
            "ref": "pkg:composer/amphp/serialization@v1.0.0"
        },
        {
            "ref": "pkg:composer/amphp/sync@v1.4.2"
        },
        {
            "ref": "pkg:composer/composer/ca-bundle@1.3.5"
        },
        {
            "ref": "pkg:composer/composer/class-map-generator@1.0.0"
        },
        {
            "ref": "pkg:composer/composer/composer@2.5.4"
        },
        {
            "ref": "pkg:composer/composer/metadata-minifier@1.0.0"
        },
        {
            "ref": "pkg:composer/composer/pcre@3.1.0"
        },
        {
            "ref": "pkg:composer/composer/semver@3.3.2"
        },
        {
            "ref": "pkg:composer/composer/spdx-licenses@1.5.7"
        },
        {
            "ref": "pkg:composer/composer/xdebug-handler@3.0.3"
        },
        {
            "ref": "pkg:composer/cyclonedx/cyclonedx-library@v1.6.3"
        },
        {
            "ref": "pkg:composer/fidry/console@0.5.5"
        },
        {
            "ref": "pkg:composer/humbug/box@4.3.7"
        },
        {
            "ref": "pkg:composer/humbug/php-scoper@0.18.2"
        },
        {
            "ref": "pkg:composer/jetbrains/phpstorm-stubs@v2022.3"
        },
        {
            "ref": "pkg:composer/justinrainbow/json-schema@5.2.12"
        },
        {
            "ref": "pkg:composer/laravel/serializable-closure@v1.3.0"
        },
        {
            "ref": "pkg:composer/nikic/iter@v2.2.0"
        },
        {
            "ref": "pkg:composer/nikic/php-parser@v4.15.4"
        },
        {
            "ref": "pkg:composer/package-url/packageurl-php@1.0.5"
        },
        {
            "ref": "pkg:composer/paragonie/constant_time_encoding@v2.6.3"
        },
        {
            "ref": "pkg:composer/paragonie/pharaoh@v0.6.0"
        },
        {
            "ref": "pkg:composer/phpdocumentor/reflection-common@2.2.0"
        },
        {
            "ref": "pkg:composer/phpdocumentor/reflection-docblock@5.3.0"
        },
        {
            "ref": "pkg:composer/phpdocumentor/type-resolver@1.6.2"
        },
        {
            "ref": "pkg:composer/phplang/scope-exit@1.0.0"
        },
        {
            "ref": "pkg:composer/psr/container@2.0.2"
        },
        {
            "ref": "pkg:composer/psr/event-dispatcher@1.0.0"
        },
        {
            "ref": "pkg:composer/psr/log@3.0.0"
        },
        {
            "ref": "pkg:composer/react/promise@v2.9.0"
        },
        {
            "ref": "pkg:composer/seld/jsonlint@1.9.0"
        },
        {
            "ref": "pkg:composer/seld/phar-utils@1.2.1"
        },
        {
            "ref": "pkg:composer/seld/signal-handler@2.0.1"
        },
        {
            "ref": "pkg:composer/swaggest/json-diff@v3.10.4"
        },
        {
            "ref": "pkg:composer/swaggest/json-schema@v0.12.41"
        },
        {
            "ref": "pkg:composer/symfony/console@v6.2.7"
        },
        {
            "ref": "pkg:composer/symfony/deprecation-contracts@v3.2.1"
        },
        {
            "ref": "pkg:composer/symfony/event-dispatcher-contracts@v3.2.1"
        },
        {
            "ref": "pkg:composer/symfony/filesystem@v6.2.7"
        },
        {
            "ref": "pkg:composer/symfony/finder@v6.2.7"
        },
        {
            "ref": "pkg:composer/symfony/polyfill-ctype@v1.27.0"
        },
        {
            "ref": "pkg:composer/symfony/polyfill-intl-grapheme@v1.27.0"
        },
        {
            "ref": "pkg:composer/symfony/polyfill-intl-normalizer@v1.27.0"
        },
        {
            "ref": "pkg:composer/symfony/polyfill-mbstring@v1.27.0"
        },
        {
            "ref": "pkg:composer/symfony/polyfill-php73@v1.27.0"
        },
        {
            "ref": "pkg:composer/symfony/process@v6.2.7"
        },
        {
            "ref": "pkg:composer/symfony/serializer@v6.2.7"
        },
        {
            "ref": "pkg:composer/symfony/service-contracts@v3.2.1"
        },
        {
            "ref": "pkg:composer/symfony/string@v6.2.7"
        },
        {
            "ref": "pkg:composer/symfony/var-dumper@v6.2.7"
        },
        {
            "ref": "pkg:composer/thecodingmachine/safe@v2.4.0"
        },
        {
            "ref": "pkg:composer/ulrichsg/getopt-php@v3.4.0"
        },
        {
            "ref": "pkg:composer/webmozart/assert@1.11.0"
        },
        {
            "ref": "pkg:composer/bartlett/box-manifest@3.x-dev%4036c1394"
        }
    ]
}

 // Writing results to standard output
```

</details>
