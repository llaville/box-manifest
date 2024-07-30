<!-- markdownlint-disable MD013 -->
# Installation

1. [Requirements](#requirements)
1. [PHAR](#phar)
1. [Docker](#docker)
1. [Phive](#phive)
1. [Composer](#composer)

## Requirements

| Version | Status                 | Requirements                                                            |
|:--------|:-----------------------|:------------------------------------------------------------------------|
| **4.x** | **Active development** | **PHP >= 8.2, ext-phar**, without patches                               |
| 3.x     | Active support         | PHP >= 8.1, ext-phar, without patches                                   |
| 2.x     | End Of Life            | PHP >= 8.1, ext-phar, cweagans/composer-patches >=1.7 with box v4 patch |
| 1.x     | End Of Life            | PHP >= 7.4, ext-phar, cweagans/composer-patches >=1.7 with box v3 patch |

## PHAR

The preferred method of installation is to use the PHPLint PHAR which can be downloaded from the most recent
[Github Release][releases]. This method ensures you will not have any dependency conflict issue.

## Docker

You can install `box-manifest` with [Docker][docker]

```shell
docker pull ghcr.io/llaville/box-manifest:v4
or
docker pull ghcr.io/llaville/box-manifest:latest
```

## Phive

You can install `box-manifest` with [Phive][phive]

```shell
phive install llaville/box-manifest --force-accept-unsigned
```

To upgrade `box-manifest` use the following command:

```shell
phive update llaville/box-manifest --force-accept-unsigned
```

## Composer

You can install `box-manifest` with [Composer][composer]

```shell
composer global require bartlett/box-manifest ^4
```

If you cannot install it because of a dependency conflict, or you prefer to install it for your project, we recommend
you to take a look at [bamarni/composer-bin-plugin][bamarni/composer-bin-plugin]. Example:

```shell
composer require --dev bamarni/composer-bin-plugin
composer bin box-manifest require --dev bartlett/box-manifest

vendor/bin/phplint
```

[releases]: https://github.com/llaville/box-manifest/releases
[composer]: https://getcomposer.org
[bamarni/composer-bin-plugin]: https://github.com/bamarni/composer-bin-plugin
[phive]: https://github.com/phar-io/phive
[docker]: https://docs.docker.com/get-docker/
