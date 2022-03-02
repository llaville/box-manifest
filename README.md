<!-- markdownlint-disable MD013 MD033 -->
# BOX Manifest

Main goal of this project is to write a manifest in any [PHP Archive (PHAR)](https://www.php.net/phar)
built with the [BOX](https://github.com/box-project/box) tool.

## Limitation

The [Metadata (`metadata`)](https://github.com/box-project/box/blob/master/doc/configuration.md#metadata-metadata) setting
of the BOX allow only a callable function that will be evaluated without any arguments.

Currently, the BOX 3.16 did not send contextual parameters (`KevinGH\Box\Configuration\Configuration` and `KevinGH\Box\Box`)
when calling the metadata callback.

This is the reason, why this project used [`cweagans/composer-patches`](https://github.com/cweagans/composer-patches)
composer plugin to patch `humbug/box` at install runtime.

## Documentation

All the documentation is available on [website](https://llaville.github.io/box-manifest/1.x),
generated from the [docs](https://github.com/llaville/box-manifest/tree/master/docs) folder.

* [Getting Started](docs/getting-started.md).

## Usage

When your metadata setting identify a callable function, this one is in charge
to call the `Bartlett\BoxManifest\Composer\ManifestFactory::create` function with any class that must implement
the `Bartlett\BoxManifest\Composer\ManifestBuilderInterface` interface.

Then proceed with the `bin/box` command to realize all BOX operations.

This project provide, by default, two basic implementations :

### Create a simple key-value pairs text format

```json
{
  "metadata": "Bartlett\\BoxManifest\\Composer\\ManifestFactory::toText"
}
```

<details>
<summary>box compile output</summary>

```text
Box version 3.16.0@adb282a

 // Loading the configuration file "/shared/backups/bartlett/box-manifest/box.json.dist".

🔨  Building the PHAR "/shared/backups/bartlett/box-manifest/box-manifest.phar"

? No compactor to register
? Adding main file: /shared/backups/bartlett/box-manifest/bin/box
? Adding requirements checker
? Adding binary files
    > No file found
? Auto-discover files? No
? Exclude dev files? Yes
? Adding files
    > 4346 file(s)
? Generating new stub
  - Using shebang line: #!/usr/bin/env php
  - Using banner:
    > Generated by Humbug Box 3.16.0@adb282a.
    >
    > @link https://github.com/humbug/box
? Setting metadata
  - Using composer.json : /shared/backups/bartlett/box-manifest/composer.json
  - Using composer.lock : /shared/backups/bartlett/box-manifest/composer.lock
  - bartlett/box-manifest: 1.x-dev@c47e100
amphp/amp: v2.6.2
amphp/byte-stream: v1.8.1
amphp/parallel: v1.4.1
amphp/parallel-functions: v1.1.0
amphp/parser: v1.0.0
amphp/process: v1.1.3
amphp/serialization: v1.0.0
amphp/sync: v1.4.2
composer/ca-bundle: 1.3.1
composer/composer: 2.2.7
composer/metadata-minifier: 1.0.0
composer/package-versions-deprecated: 1.11.99.5
composer/pcre: 1.0.1
composer/semver: 3.2.9
composer/spdx-licenses: 1.5.6
composer/xdebug-handler: 3.0.3
cweagans/composer-patches: 1.7.2
doctrine/instantiator: 1.4.0
fidry/console: 0.4.0
humbug/box: 3.16.0
humbug/php-scoper: 0.17.2
jetbrains/phpstorm-stubs: v2021.3
justinrainbow/json-schema: 5.2.11
laravel/serializable-closure: v1.1.1
myclabs/deep-copy: 1.10.2
nikic/iter: v2.2.0
nikic/php-parser: v4.13.2
paragonie/constant_time_encoding: v2.5.0
paragonie/pharaoh: v0.6.0
paragonie/random_compat: v9.99.100
paragonie/sodium_compat: v1.17.0
phar-io/manifest: 2.0.3
phar-io/version: 3.2.1
phpdocumentor/reflection-common: 2.2.0
phpdocumentor/reflection-docblock: 5.3.0
phpdocumentor/type-resolver: 1.6.0
phpspec/prophecy: v1.15.0
phpunit/php-code-coverage: 9.2.13
phpunit/php-file-iterator: 3.0.6
phpunit/php-invoker: 3.1.1
phpunit/php-text-template: 2.0.4
phpunit/php-timer: 5.0.3
phpunit/phpunit: 9.5.16
psr/container: 1.1.2
psr/event-dispatcher: 1.0.0
psr/log: 1.1.4
react/promise: v2.9.0
sebastian/cli-parser: 1.0.1
sebastian/code-unit: 1.0.8
sebastian/code-unit-reverse-lookup: 2.0.3
sebastian/comparator: 4.0.6
sebastian/complexity: 2.0.2
sebastian/diff: 4.0.4
sebastian/environment: 5.1.3
sebastian/exporter: 4.0.4
sebastian/global-state: 5.0.5
sebastian/lines-of-code: 1.0.3
sebastian/object-enumerator: 4.0.4
sebastian/object-reflector: 2.0.4
sebastian/recursion-context: 4.0.4
sebastian/resource-operations: 3.0.3
sebastian/type: 2.3.4
sebastian/version: 3.0.2
seld/jsonlint: 1.8.3
seld/phar-utils: 1.2.0
symfony/console: v5.4.5
symfony/deprecation-contracts: v2.5.0
symfony/event-dispatcher-contracts: v2.5.0
symfony/filesystem: v5.4.5
symfony/finder: v5.4.3
symfony/polyfill-ctype: v1.24.0
symfony/polyfill-intl-grapheme: v1.24.0
symfony/polyfill-intl-normalizer: v1.24.0
symfony/polyfill-mbstring: v1.24.0
symfony/polyfill-php80: v1.24.0
symfony/polyfill-php81: v1.24.0
symfony/process: v5.4.5
symfony/serializer: v5.4.5
symfony/service-contracts: v2.5.0
symfony/string: v5.4.3
symfony/var-dumper: v5.4.5
thecodingmachine/safe: v1.3.3
theseer/tokenizer: 1.2.1
ulrichsg/getopt-php: v3.4.0
webmozart/assert: 1.10.0
webmozart/path-util: 2.3.0
? Dumping the Composer autoloader
? Removing the Composer dump artefacts
? Compressing with the algorithm "GZ"
    > Warning: the extension "zlib" will now be required to execute the PHAR
? Setting file permissions to 0755
* Done.

No recommendation found.
No warning found.

 // PHAR: 4369 files (8.09MB)
 // You can inspect the generated PHAR with the "info" command.

 // Memory usage: 86.19MB (peak: 87.40MB), time: 6secs
```

</details>

### Read information of the PHP Archive with BOX

For example : `box info /path/to/box-manifest.phar`

With a manifest in TEXT format

<details>
<summary>box info output</summary>

```text
API Version: 1.1.0

Compression: GZ

Signature: SHA-1
Signature Hash: C7EAC341FA249E34DD220E3B55FDDD710BE87C27

Metadata:
'bartlett/box-manifest: dev-master
amphp/amp: v2.6.1
amphp/byte-stream: v1.8.1
amphp/parallel: v1.4.1
amphp/parallel-functions: v1.1.0
amphp/parser: v1.0.0
amphp/process: v1.1.3
amphp/serialization: v1.0.0
amphp/sync: v1.4.2
composer/package-versions-deprecated: 1.11.99.5
composer/pcre: 1.0.1
composer/semver: 3.2.9
composer/xdebug-handler: 3.0.1
cweagans/composer-patches: 1.7.2
fidry/console: 0.2.0
humbug/box: 3.16.0
humbug/php-scoper: 0.17.0
jetbrains/phpstorm-stubs: v2021.3
justinrainbow/json-schema: 5.2.11
laravel/serializable-closure: v1.1.1
nikic/iter: v2.2.0
nikic/php-parser: v4.13.2
paragonie/constant_time_encoding: v2.5.0
paragonie/pharaoh: v0.6.0
paragonie/random_compat: v9.99.100
paragonie/sodium_compat: v1.17.0
phpdocumentor/reflection-common: 2.2.0
phpdocumentor/reflection-docblock: 5.3.0
phpdocumentor/type-resolver: 1.6.0
psr/container: 1.1.2
psr/event-dispatcher: 1.0.0
psr/log: 1.1.4
seld/jsonlint: 1.8.3
symfony/console: v5.4.3
symfony/deprecation-contracts: v2.5.0
symfony/event-dispatcher-contracts: v2.5.0
symfony/filesystem: v5.4.3
symfony/finder: v5.4.3
symfony/polyfill-ctype: v1.24.0
symfony/polyfill-intl-grapheme: v1.24.0
symfony/polyfill-intl-normalizer: v1.24.0
symfony/polyfill-mbstring: v1.24.0
symfony/polyfill-php80: v1.24.0
symfony/polyfill-php81: v1.24.0
symfony/process: v5.4.3
symfony/serializer: v5.4.3
symfony/service-contracts: v2.5.0
symfony/string: v5.4.3
symfony/var-dumper: v5.4.3
thecodingmachine/safe: v1.3.3
ulrichsg/getopt-php: v3.4.0
webmozart/assert: 1.10.0
webmozart/path-util: 2.3.0'

Contents: 3035 files (6.25MB)

 // Use the --list|-l option to list the content of the PHAR.
```

</details>

## Contributors

* Laurent Laville (Lead Developer)
