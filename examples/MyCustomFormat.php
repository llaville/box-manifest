<?php

use Bartlett\BoxManifest\Composer\ManifestBuilderInterface;

class MyCustomFormat implements ManifestBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(array $content): string
    {
        return var_export($content['installed.php'], true);
    }
}
