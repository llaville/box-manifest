<!-- markdownlint-disable MD013 MD029 MD033 -->
# Use generated files produced by `bin/box-manifest` command

Begins by generate the manifest files.

1. First we will build a SBOM XML version following default (CycloneDX) specification 1.5

```shell
box-manifest manifest:build --config app-fixtures-box.json --output-file sbom.xml -v
```

That prints such results :

```text

 // Loading the configuration file "app-fixtures-box.json".

 // Writing manifest to file "/path/to/examples/app-fixtures/sbom.xml"

```

2. Second we will build a decorated TEXT version

```shell
box-manifest manifest:build --config app-fixtures-box.json --output-file manifest.txt -v
```

That prints such results :

```text

 // Loading the configuration file "app-fixtures-box.json".

 // Writing manifest to file "/path/to/examples/app-fixtures/manifest.txt"

```

Now its turn to declare these files to the BOX config file, with :

```json
{
    "files-bin": [
      "sbom.xml",
      "manifest.txt"
    ]
}
```

Then finally, compile your PHP Archive with (or without `--boostrap` option),
the metadata contents is only used as fallback contents in case you forgot to declare `files-bin` entries.

```shell
box-manifest box:compile --config app-fixtures-box.json --bootstrap bootstrap.php -v
```

<details>
<summary>TL;DR output</summary>

```text
    ____
   / __ )____  _  __
  / __  / __ \| |/_/
 / /_/ / /_/ />  <
/_____/\____/_/|_|


Box version 4.3.8@5534406

 // Loading the configuration file "app-fixtures-box.json".

🔨  Building the PHAR "/path/to/examples/app-fixtures/app-fixtures.phar"

? Checking Composer compatibility
    > '/usr/local/bin/composer' '--version'
    > 2.6.4 (Box requires ^2.2.0)
    > Supported version detected
? No compactor to register
? Adding main file: /path/to/examples/app-fixtures/index.php
? Adding requirements checker
? Adding binary files
    > 36 file(s)
? Auto-discover files? No
? Exclude dev files? Yes
? Adding files
    > 25 file(s)
? Using stub file: /path/to/examples/app-fixtures/app-fixtures-stub.php
? Setting metadata
  - root/app-fixtures: 3.x-dev@9661882
psr/log: 3.0.0
? Dumping the Composer autoloader
    > '/usr/local/bin/composer' 'dump-autoload' '--classmap-authoritative' '--no-dev' '--ansi'
Generating optimized autoload files (authoritative)
Generated optimized autoload files (authoritative) containing 1 classes

? Removing the Composer dump artefacts
? Compressing with the algorithm "GZ"
    > Warning: the extension "zlib" will now be required to execute the PHAR
? Setting file permissions to 0755
* Done.

No recommendation found.
⚠️  1 warning found:
    - The "alias" setting has been set but is ignored since a custom stub path is used

 // PHAR: 60 files (48.27KB)
 // You can inspect the generated PHAR with the "info" command.

 // Memory usage: 12.36MB (peak: 12.82MB), time: <1sec


 // Loading the configuration file "app-fixtures-box.json".

```

</details>

To verify contents of PHAR `metadata` field, you can run following command with CLI

```shell
php -r "var_export((new Phar(getcwd() . '/app-fixtures.phar'))->getMetadata());"
```

And contents of binary files with following CLI command

```shell
php -r "var_export((new Phar(getcwd() . '/app-fixtures.phar'))['sbom.xml']->getContent());"
or
php -r "var_export((new Phar(getcwd() . '/app-fixtures.phar'))['manifest.txt']->getContent());"
```
