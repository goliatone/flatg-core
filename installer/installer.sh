#!/bin/bash
###############################
# bash <(curl -s https://raw.github.com/goliatone/flatg-core/master/installer/install.sh)
###############################
FLATG_RELEASE=v0.0.0

echo "Downloading composer.json file..."
COMPOSER_URL="https://raw.github.com/goliatone/flatg-core/master/installer/composer.json" 
curl -L -O $COMPOSER_URL

echo "Execute composer"
composer install

echo "Creating flatg dir structure at $ROOT..."
mkdir -p ${ROOT}/{assets,articles,themes}