<!-- markdownlint-disable MD013 MD033 -->
# Box Manifest

<p align="center">
    <img src="assets/images/box.png" width=128 alt="Box Manifest logo" />
    <font size="100%">Building and managing Manifest to PHP Archives</font>
</p>

[Get Started](./getting-started.md){ .md-button .md-button--primary }
[Learn more](#everything-you-would-expect-but-not-have-yet-with-phars){ .md-button }
[Watch :material-movie-play:](#play-terminal-sessions){ .md-button }

## Features at a glance

| Feature                                                         |          Legacy Command           |              Pipeline Command              |
|-----------------------------------------------------------------|:---------------------------------:|:------------------------------------------:|
| Creates a manifest of your software components and dependencies | `manifest:build` :material-check: |     `make build` [^1] :material-check:     |
| Generates a stub for your manifest application                  | `manifest:stub` :material-check:  |       `make stub`  :material-check:        |
| Generates a final BOX configuration file                        |         :material-close:          |     `make configure` :material-check:      |
| Compiles an application into a PHAR                             |         :material-close:          |    `make compile` [^2] :material-check:    |
| Adds custom action easily                                       |         :material-close:          | `make MyCustomStage` [^3] :material-check: |

[^1]: You can create multiple manifest on same pipeline command, while legacy command allowed only once at a time.
[^2]: Easy launcher of [BOX project][box-project] binary command (`vendor/bin/box`)
[^3]:
    `MyCustomStage` is a custom class that should implement the `Bartlett\BoxManifest\Pipeline\StageInterface` contract
    and must be loadable, either by your autoloader or with bootstrap helper feature (see `--bootstrap|-b` option).

## Everything you would expect but not have yet with PHARs

<div class="grid cards" markdown>

- :material-clock-fast:{ .lg .middle } __Set up in less than 5 minutes__

    ---

    Install `box-manifest`with [`composer`][composer] and get up
    and running in minutes

    [:octicons-arrow-right-24: Getting started](./getting-started.md)

- :material-box:{ .lg .middle } __Capabilities__

    ---

    Provides advanced, inventory software capabilities to PHARs

    [:octicons-arrow-right-24: Pipeline reference](./capabilities.md)

- :material-power-settings:{ .lg .middle } __Zero configuration by default__

    ---

    As [`BOX`][box-project], no configuration required to start

    [:octicons-arrow-right-24: Customization](./stages/README.md)

- :material-magnify-expand:{ .lg .middle } __Inspect__

    ---

    Inspect manifests contents of a PHAR at runtime.

    [:octicons-arrow-right-24: Inspect](./inspect.md)

- :material-scale-balance:{ .lg .middle } __Open Source, MIT__

    ---

    BOX Manifest is licensed under MIT and available on [GitHub][github-repo]

    [:octicons-arrow-right-24: License](https://github.com/llaville/box-manifest?tab=MIT-1-ov-file#readme)

</div>

## Play terminal sessions

<div class="annotate" markdown>

Play following movies (1), and see how it's so easy to include some manifests in your PHAR distribution.

</div>

1. Created by [asciinema][asciinema] and play online with [asciinema-player][asciinema-player] with help of this [Mkdocs plugin][mkdocs-asciinema-player]

### Installation

```asciinema-player
{
    "file": "./assets/asciinema/installation.cast"
}
```

### Make your first manifests and stub

```asciinema-player
{
    "file": "./assets/asciinema/make.cast"
}
```

[box-project]: https://github.com/box-project/box
[github-repo]: https://github.com/llaville/box-manifest
[composer]: https://getcomposer.org/
[asciinema]: https://asciinema.org/
[asciinema-player]: https://github.com/asciinema/asciinema-player
[mkdocs-asciinema-player]: https://github.com/pa-decarvalho/mkdocs-asciinema-player
