#!/bin/bash
###############################
# bash <(curl -s https://raw.github.com/goliatone/flatg-core/master/installer/install.sh)
###############################
FLATG_RELEASE=v0.0.0
ROOT='./'
THEME_URL=
echo "Downloading composer.json file..."
COMPOSER_URL="https://raw.github.com/goliatone/flatg-core/master/installer/composer.json" 
curl -L -O $COMPOSER_URL

echo "Execute composer"
composer install

echo "Creating flatg dir structure at $ROOT..."
mkdir -p ${ROOT}/{assets,articles,themes}

THEME_RELEASE=master
THEME="https://github.com/goliatone/flatg-core/archive/${THEME_RELEASE}.tar.gz" 

echo "Downloading flatg theme..."
popd ./themes
curl -L -O $THEME
# github will redirect this link, you end up having name missmatch
THEME_DIR=$(tar -ztf "${THEME_RELEASE}.tar.gz" | head -n 1)
tar -zxvf "${THEME_RELEASE}.tar.gz"