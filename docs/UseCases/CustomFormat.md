<!-- markdownlint-disable MD013 MD033 -->
# Manifest in free format

Additional free format is in charge of User to implement it.
See class `Bartlett\BoxManifest\Tests\fixtures\ConsoleManifest` in file `tests/fixtures/my-manifest.php` (for example).

Run following command :

```shell
bin/box-manifest manifest:build --bootstrap tests/fixtures/my-manifest.php --format Bartlett\\BoxManifest\\Tests\\fixtures\\ConsoleManifest -v
```

That will print following results :

<details>
<summary>standard output contents</summary>

```text

 // Loading the configuration file "/path/to/box.json".


+-------------------------------------------+-----------------+
| Package                                   | Version         |
+-------------------------------------------+-----------------+
| bartlett/box-manifest                     | 3.x-dev@5e593f3 |
| requires php ^8.1                         | 8.1.17          |
| requires ext-phar *                       | 8.1.17          |
| uses amphp/amp                            | v2.6.2          |
| uses amphp/byte-stream                    | v1.8.1          |
| uses amphp/parallel                       | v1.4.2          |
| uses amphp/parallel-functions             | v1.1.0          |
| uses amphp/parser                         | v1.1.0          |
| uses amphp/process                        | v1.1.4          |
| uses amphp/serialization                  | v1.0.0          |
| uses amphp/sync                           | v1.4.2          |
| uses composer/ca-bundle                   | 1.3.5           |
| uses composer/class-map-generator         | 1.0.0           |
| requires composer/composer ^2.2           | 2.5.4           |
| uses composer/metadata-minifier           | 1.0.0           |
| uses composer/pcre                        | 3.1.0           |
| uses composer/semver                      | 3.3.2           |
| uses composer/spdx-licenses               | 1.5.7           |
| uses composer/xdebug-handler              | 3.0.3           |
| requires cyclonedx/cyclonedx-library ^2.0 | v2.0.0          |
| uses fidry/console                        | 0.5.5           |
| requires humbug/box ^4.0                  | 4.3.8           |
| uses humbug/php-scoper                    | 0.18.3          |
| uses jetbrains/phpstorm-stubs             | v2022.3         |
| uses justinrainbow/json-schema            | 5.2.12          |
| uses laravel/serializable-closure         | v1.3.0          |
| uses nikic/iter                           | v2.2.0          |
| uses nikic/php-parser                     | v4.15.4         |
| uses opis/json-schema                     | 2.3.0           |
| uses opis/string                          | 2.0.1           |
| uses opis/uri                             | 1.1.0           |
| uses package-url/packageurl-php           | 1.0.6           |
| uses paragonie/constant_time_encoding     | v2.6.3          |
| uses phpdocumentor/reflection-common      | 2.2.0           |
| uses phpdocumentor/reflection-docblock    | 5.3.0           |
| uses phpdocumentor/type-resolver          | 1.6.2           |
| uses psr/container                        | 2.0.2           |
| uses psr/event-dispatcher                 | 1.0.0           |
| uses psr/log                              | 3.0.0           |
| uses react/promise                        | v2.9.0          |
| uses seld/jsonlint                        | 1.9.0           |
| uses seld/phar-utils                      | 1.2.1           |
| uses seld/signal-handler                  | 2.0.1           |
| uses symfony/console                      | v6.2.7          |
| uses symfony/deprecation-contracts        | v3.2.1          |
| uses symfony/event-dispatcher-contracts   | v3.2.1          |
| requires symfony/filesystem ^6.1          | v6.2.7          |
| uses symfony/finder                       | v6.2.7          |
| uses symfony/polyfill-ctype               | v1.27.0         |
| uses symfony/polyfill-intl-grapheme       | v1.27.0         |
| uses symfony/polyfill-intl-normalizer     | v1.27.0         |
| uses symfony/polyfill-mbstring            | v1.27.0         |
| uses symfony/polyfill-php73               | v1.27.0         |
| uses symfony/process                      | v6.2.7          |
| requires symfony/runtime ^6.1             | v6.2.7          |
| requires symfony/serializer ^6.1          | v6.2.7          |
| uses symfony/service-contracts            | v3.2.1          |
| uses symfony/string                       | v6.2.7          |
| uses symfony/var-dumper                   | v6.2.7          |
| uses thecodingmachine/safe                | v2.4.0          |
| uses webmozart/assert                     | 1.11.0          |
+-------------------------------------------+-----------------+

 // Writing results to standard output

```

</details>

The `--bootstrap` option is used to load resource (class) that implement the `Bartlett\BoxManifest\Composer\ManifestBuilderInterface` contract.

The `--format` option named the class that should implement the `ManifestBuilderInterface`.
This class must be loadable with your autoloader or through the `--bootstrap` option.
