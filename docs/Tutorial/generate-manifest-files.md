<!-- markdownlint-disable MD013 MD029 MD033 -->
# Use generated files produced by `bin/box-manifest` command

Begins by generate the manifest files.

1. First we will build a SBOM XML version following default (CycloneDX) specification 1.6

```shell
box-manifest build --config app-fixtures.box.json --output-file sbom.xml
```

2. Second we will build a decorated TEXT version

```shell
box-manifest build --config app-fixtures.box.json --output-file manifest.txt --output-format ansi
```

Now its turn to declare these files to the BOX config file, with :

```json
{
    "files-bin": ["sbom.xml", "manifest.txt"]
}
```

Then finally, compile your PHP Archive with `box compile` command,
the metadata contents is only used as fallback contents in case you forgot to declare `files-bin` entries.

For example :

```shell
vendor/bin/box compile --config app-fixtures.box.json.dist
```

<details>
<summary>TL;DR output</summary>

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
