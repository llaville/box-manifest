FROM php:8.0-cli-alpine

# https://github.com/opencontainers/image-spec/blob/main/annotations.md

LABEL org.opencontainers.image.title="llaville/box-manifest"
LABEL org.opencontainers.image.description="Docker image of bartlett/box-manifest Composer package"
LABEL org.opencontainers.image.source="https://github.com/llaville/box-manifest"
LABEL org.opencontainers.image.version="1.0.0"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.authors="llaville"

RUN $(php -r '$extensionInstalled = \array_map("strtolower", \get_loaded_extensions(false));$requiredExtensions = ["zlib", "phar", "openssl", "pcre", "tokenizer", "filter"];$extensionsToInstall = \array_diff($requiredExtensions, $extensionInstalled);if ([] !== $extensionsToInstall) {echo \sprintf("docker-php-ext-install %s", \implode(" ", $extensionsToInstall));}echo "echo \"No extensions\"";')

COPY bin /usr/local/src/box-manifest/bin
COPY src /usr/local/src/box-manifest/src
COPY vendor /usr/local/src/box-manifest/vendor

ENV BOX_REQUIREMENT_CHECKER=0
ENV PATH=$PATH:/usr/local/src/box-manifest/vendor/bin

WORKDIR /usr/src

ENTRYPOINT ["/usr/local/src/box-manifest/bin/box"]