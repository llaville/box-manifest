<!-- markdownlint-disable MD013 MD024 -->
# Changes in 1.x

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [1.2.0] - 2022-11-24

**WARNING**: This is the last version to support BOX v3. Upcoming Box Manifest 2.0 will only support BOX v4 !

### Changed

- Improve BOX v3 patch and synchronize with BOX v4 support by adding a `compile` `--bootstrap` option

## [1.1.0] - 2022-09-13

### Changed

When package version is a branch alias, print branch name with commit reference.

i.e:

```text
cweagans/composer-patches: 1.7.2
graphp/graph: dev-master@04461a7
graphp/graphviz: dev-master@42a2098
```

Instead of only

```text
cweagans/composer-patches: 1.7.2
graphp/graph: dev-master
graphp/graphviz: dev-master
```

### Fixed

Avoid Fatal Error when local project autoloader does not exist.

## [1.0.2] - 2022-02-28

### Fixed

Remove `check-requirements` setting restriction introduced with version 1.0.1 only on PHAR distribution.

## [1.0.1] - 2022-02-28

**CAUTION** If you want to use the PHAR version of `bartlett/box-manifest`, the BOX
[check-requirements](https://github.com/box-project/box/blob/master/doc/configuration.md#check-requirements-check-requirements)
setting is not supported !

Docker and Composer Versions are not affected by this restriction.

### Added

- Dockerfile to build docker image (available [online](https://github.com/llaville/box-manifest/pkgs/container/box-manifest)) of this package

### Changed

- Patch for official `humbug/box` project on version 3.16 to support both BOX and user project scope with autoloader

### Removed

- Follows recommendation at <https://docs.github.com/en/actions/creating-actions/dockerfile-support-for-github-actions#workdir>

## [1.0.0] - 2022-02-20

- First release with basic features. Read online [documentation](https://llaville.github.io/box-manifest/1.x/) to learn more.
