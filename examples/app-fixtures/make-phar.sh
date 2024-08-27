#!/usr/bin/env bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

BINARY_COMMAND=$SCRIPT_DIR/../../bin/box-manifest

BOX_CONFIG_FILE=app-fixtures.box.json

PHAR_STUB_FILE=app-fixtures-stub.php

$BINARY_COMMAND make -r sbom.xml -r console-style.txt build
$BINARY_COMMAND make -c $BOX_CONFIG_FILE --output-stub $PHAR_STUB_FILE stub
$BINARY_COMMAND make -c $BOX_CONFIG_FILE --output-stub $PHAR_STUB_FILE --output-conf $BOX_CONFIG_FILE.dist configure
$BINARY_COMMAND make -c $BOX_CONFIG_FILE.dist compile -vvv
