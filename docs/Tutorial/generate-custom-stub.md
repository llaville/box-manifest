<!-- markdownlint-disable MD013 MD029 MD033 -->
# Last but not the least, include a custom stub to display manifests

Of course running CLI commands to verify contents is not so hard, but we may expect a better user experience.
Adding a `--manifest` option that will either :

- search for the first manifest available in a priority files list
- show a specific version

Create, for example this custom stub, either manually or with the `bin/box-stub` command.

```php
<?php
Phar::mapPhar('app-fixtures-alias.phar');

if ($argc > 1 && $argv[1] === '--manifest') {
    $resources = ($argc > 2 && !str_starts_with($argv[2], '-')) ? [$argv[2]] : ['manifest.txt', 'manifest.xml', 'sbom.xml', 'sbom.json'];

    foreach ($resources as $resource) {
        $filename = "phar://app-fixtures-alias.phar/{$resource}";
        if (file_exists($filename)) {
            echo (file_get_contents($filename));
            $status = 0;
            break;
        } elseif (count($resources) === 1) {
            echo sprintf('Manifest "%s" is not available in this PHP Archive.', $resource), PHP_EOL;
            $status = 1;
            break;
        }
    }
    exit($status);
}

require 'phar://app-fixtures-alias.phar/.box/bin/check-requirements.php';
require 'phar://app-fixtures-alias.phar/index.php';

__HALT_COMPILER(); ?>
```

And declare it, in the BOX config file `app-fixtures-box.json`, with excerpt :

```json
{
  "stub": "app-fixtures-stub.php"
}
```

Then re-compile for the last time your PHP Archive.

You are now able to display either the first manifest available (`manifest.txt` is included and is on top or priority list)

```shell
./app-fixtures.phar --manifest
```

That prints following results :

```text
root/app-fixtures: 3.x-dev@9661882
psr/log: 3.0.0
```

Or a specific version, like :

```shell
./app-fixtures.phar --manifest sbom.xml
```

That prints following results :

```xml
<?xml version="1.0" encoding="UTF-8"?>
<bom xmlns="http://cyclonedx.org/schema/bom/1.4" version="1">
  <metadata>
    <tools>
      <tool>
        <vendor><![CDATA[box-project]]></vendor>
        <name><![CDATA[box]]></name>
        <version><![CDATA[4.3.8@5534406]]></version>
      </tool>
    </tools>
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

And if you ask for an unavailable version into the PHP Archive,

```shell
./app-fixtures.phar --manifest sbom.json
```

You'll get

```text
Manifest "sbom.json" is not available in this PHP Archive.
```
