<!-- markdownlint-disable MD013 -->
# Getting Started

## Requirements

* PHP 8.1 or greater
* ext-phar
* PHPUnit 9 or greater (if you want to run unit tests)

![GraPHP Composer](./graph-composer.svg)

Generated with [fork](https://github.com/markuspoerschke/graph-composer/tree/add-options-to-exclude) of [clue/graph-composer](https://github.com/clue/graph-composer).
Read more on [PR request](https://github.com/clue/graph-composer/pull/45).

## Installation

### With Composer

Install the BOX Manifest with [Composer](https://getcomposer.org/).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```shell
composer require bartlett/box-manifest ^2
```

### With Git

The BOX Manifest can be directly used from [GitHub](https://github.com/llaville/box-manifest.git)
by cloning the repository into a directory of your choice.

```shell
git clone --branch 2.x https://github.com/llaville/box-manifest.git
```

### With Docker

```shell
docker pull ghcr.io/llaville/box-manifest:v2
```

## Quick Started

### Basic Usage

Declare your manifest format in your `box.json` or `box.json.dist` configuration file (`metadata` setting).

basic simple text :

```json
{
  "metadata": "Bartlett\\BoxManifest\\Composer\\ManifestFactory::toText"
}
```

decorated text :

```json
{
  "metadata": "Bartlett\\BoxManifest\\Composer\\ManifestFactory::toHighlight"
}
```

Then build your PHP Archive (PHAR) via the `compile` command.

**CAUTION:** When your callable function for metadata is embedded to Box Manifest distribution (like in these examples),
you don't need to use the `--bootstrap` option. Otherwise, you should specify it in command line to load the autoloader
that is able to load the metadata callable function.

#### Console CLI

```shell
box compile
```

or

```shell
box compile -c box.json.dist
```

or

```shell
box compile -c box.json --bootstrap vendor/autoload.php
```

### Docker CLI

> Please mount the code directory to `/usr/src` in the container.

```shell
docker run --rm -it -u "$(id -u):$(id -g)" -v $(pwd):/usr/src -w /usr/src ghcr.io/llaville/box-manifest:v2 compile
```

or

```shell
docker run --rm -it -u "$(id -u):$(id -g)" -v $(pwd):/usr/src -w /usr/src ghcr.io/llaville/box-manifest:v2 compile -c box.json.dist
```

or

```shell
docker run --rm -it -u "$(id -u):$(id -g)" -v $(pwd):/usr/src -w /usr/src ghcr.io/llaville/box-manifest:v2 compile -c box.json --bootstrap vendor/autoload.php
```

### Examples

Manifest of `bartlett/box-manifest` in TEXT format, using `"metadata": "Bartlett\\BoxManifest\\Composer\\ManifestFactory::toText"`

```text
  - bartlett/box-manifest: 2.x-dev@3e58f10
amphp/amp: v2.6.2
amphp/byte-stream: v1.8.1
amphp/parallel: v1.4.2
amphp/parallel-functions: v1.1.0
amphp/parser: v1.1.0
amphp/process: v1.1.4
amphp/serialization: v1.0.0
amphp/sync: v1.4.2
composer/ca-bundle: 1.3.5
composer/class-map-generator: 1.0.0
composer/composer: 2.5.1
composer/metadata-minifier: 1.0.0
composer/pcre: 3.1.0
composer/semver: 3.3.2
composer/spdx-licenses: 1.5.7
composer/xdebug-handler: 3.0.3
cweagans/composer-patches: 1.7.3
fidry/console: 0.5.5
humbug/box: 4.2.0
humbug/php-scoper: 0.18.2
jetbrains/phpstorm-stubs: v2022.3
justinrainbow/json-schema: 5.2.12
laravel/serializable-closure: v1.2.2
nikic/iter: v2.2.0
nikic/php-parser: v4.15.2
paragonie/constant_time_encoding: v2.6.3
paragonie/pharaoh: v0.6.0
phpdocumentor/reflection-common: 2.2.0
phpdocumentor/reflection-docblock: 5.3.0
phpdocumentor/type-resolver: 1.6.2
psr/container: 2.0.2
psr/event-dispatcher: 1.0.0
psr/log: 3.0.0
react/promise: v2.9.0
seld/jsonlint: 1.9.0
seld/phar-utils: 1.2.1
seld/signal-handler: 2.0.1
symfony/console: v6.2.3
symfony/deprecation-contracts: v3.2.0
symfony/event-dispatcher-contracts: v3.2.0
symfony/filesystem: v6.2.0
symfony/finder: v6.2.3
symfony/polyfill-ctype: v1.27.0
symfony/polyfill-intl-grapheme: v1.27.0
symfony/polyfill-intl-normalizer: v1.27.0
symfony/polyfill-mbstring: v1.27.0
symfony/polyfill-php73: v1.27.0
symfony/process: v6.2.0
symfony/serializer: v6.2.3
symfony/service-contracts: v3.2.0
symfony/string: v6.2.2
symfony/var-dumper: v6.2.3
thecodingmachine/safe: v2.4.0
ulrichsg/getopt-php: v3.4.0
webmozart/assert: 1.11.0
```

Manifest of  `` in DECORATED TEXT format, using `"metadata": "Bartlett\\BoxManifest\\Composer\\ManifestFactory::toHighlight"`

```text
  - bartlett/box-manifest: 2.x-dev@3e58f10
 requires php ^8.1: 8.1.7
 requires ext-phar *: 8.1.7
 uses amphp/amp : v2.6.2
 uses amphp/byte-stream : v1.8.1
 uses amphp/parallel : v1.4.2
 uses amphp/parallel-functions : v1.1.0
 uses amphp/parser : v1.1.0
 uses amphp/process : v1.1.4
 uses amphp/serialization : v1.0.0
 uses amphp/sync : v1.4.2
 uses composer/ca-bundle : 1.3.5
 uses composer/class-map-generator : 1.0.0
 requires composer/composer ^2.2: 2.5.1
 uses composer/metadata-minifier : 1.0.0
 uses composer/pcre : 3.1.0
 uses composer/semver : 3.3.2
 uses composer/spdx-licenses : 1.5.7
 uses composer/xdebug-handler : 3.0.3
 requires cweagans/composer-patches ^1.7: 1.7.3
 uses fidry/console : 0.5.5
 requires humbug/box ^4.0: 4.2.0
 uses humbug/php-scoper : 0.18.2
 uses jetbrains/phpstorm-stubs : v2022.3
 uses justinrainbow/json-schema : 5.2.12
 uses laravel/serializable-closure : v1.2.2
 uses nikic/iter : v2.2.0
 uses nikic/php-parser : v4.15.2
 uses paragonie/constant_time_encoding : v2.6.3
 uses paragonie/pharaoh : v0.6.0
 uses phpdocumentor/reflection-common : 2.2.0
 uses phpdocumentor/reflection-docblock : 5.3.0
 uses phpdocumentor/type-resolver : 1.6.2
 uses psr/container : 2.0.2
 uses psr/event-dispatcher : 1.0.0
 uses psr/log : 3.0.0
 uses react/promise : v2.9.0
 uses seld/jsonlint : 1.9.0
 uses seld/phar-utils : 1.2.1
 uses seld/signal-handler : 2.0.1
 uses symfony/console : v6.2.3
 uses symfony/deprecation-contracts : v3.2.0
 uses symfony/event-dispatcher-contracts : v3.2.0
 requires symfony/filesystem ^6.1: v6.2.0
 uses symfony/finder : v6.2.3
 uses symfony/polyfill-ctype : v1.27.0
 uses symfony/polyfill-intl-grapheme : v1.27.0
 uses symfony/polyfill-intl-normalizer : v1.27.0
 uses symfony/polyfill-mbstring : v1.27.0
 uses symfony/polyfill-php73 : v1.27.0
 uses symfony/process : v6.2.0
 requires symfony/serializer ^6.1: v6.2.3
 uses symfony/service-contracts : v3.2.0
 uses symfony/string : v6.2.2
 uses symfony/var-dumper : v6.2.3
 uses thecodingmachine/safe : v2.4.0
 uses ulrichsg/getopt-php : v3.4.0
 uses webmozart/assert : 1.11.0
```
