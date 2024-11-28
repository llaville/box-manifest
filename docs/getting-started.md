<!-- markdownlint-disable MD013 MD028 MD033 MD046 -->
# Getting Started

Box Manifest is a powerful PHAR framework on top of [BOX project][box-project], that simplifies the PHAR building process.

If you're familiar with PHP, you can install Box Manifest with [`composer`][composer], the PHP package manager.
If not, we recommend using [`docker`][docker].

## Requirements

* PHP 8.2 or greater
* ext-phar
* PHPUnit 11 or greater (if you want to run unit tests)

## Installation

### with composer <small>recommended</small> { #with-composer data-toc-label="with composer" }

=== "Latest"

    ```shell
    composer require bartlett/box-manifest
    ```

=== "4.x"

    ```shell
    composer require bartlett/box-manifest ^4.1
    ```

> [!TIP]
>
> If you cannot install it because of a dependency conflict, or you prefer to install it for your project, we recommend
> you to take a look at [bamarni/composer-bin-plugin][bamarni/composer-bin-plugin].
>
> Example:
>
> ```shell
> composer require --dev bamarni/composer-bin-plugin
> composer bin box-manifest require --dev bartlett/box-manifest
> ```

### with docker

=== "Latest"

    ```shell
    docker pull ghcr.io/llaville/box-manifest
    ```

=== "4.x"

    ```shell
    docker pull ghcr.io/llaville/box-manifest:v4
    ```

### with phive

[PHIVE][phive] : The Phar Installation and Verification Environment

=== "Globally"

    Install

    ```shell
    phive install llaville/box-manifest --force-accept-unsigned
    ```

    or update previous installation

    ```shell
    phive update llaville/box-manifest --force-accept-unsigned
    ```

=== "Locally"

    To your project, with an XML configuration file :

    === ":octicons-file-code-16: .phive/phars.xml"

        ```xml
        <?xml version="1.0" encoding="UTF-8"?>
        <phive xmlns="https://phar.io/phive">
            <phar name="llaville/box-manifest" version="^4.1" copy="false" />
        </phive>
        ```

    === "Command(s)"

        ```shell
        phive install --force-accept-unsigned
        ```

        or

        ```shell
        phive update --force-accept-unsigned
        ```

### with git

Box Manifest can be directly used from [GitHub][github-repo] by cloning the repository into a subfolder of your project root
which might be useful if you want to use the very latest version.

=== "Master"

    ```shell
    git clone https://github.com/llaville/box-manifest.git
    ```

=== "4.x"

    ```shell
    git clone -b 4.x https://github.com/llaville/box-manifest.git
    ```

Next, install the dependencies

```shell
composer update
```

[box-project]: https://github.com/box-project/box
[bamarni/composer-bin-plugin]: https://github.com/bamarni/composer-bin-plugin
[composer]: https://getcomposer.org/
[docker]: https://docs.docker.com/get-docker/
[phive]: https://github.com/phar-io/phive
[github-repo]: https://github.com/llaville/box-manifest.git
