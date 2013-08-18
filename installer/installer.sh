#!/bin/bash
#############################################################################################
# bash <(curl -s https://raw.github.com/goliatone/flatg-core/master/installer/installer.sh)
#############################################################################################
# MAKE SURE WE HAVE SYSTEM REQUIREMENTS.
#############################################################################################
check_system_requirements(){
	#we need composer
	hash composer 2>/dev/null || { 
		read -p "I require composer but it's not installed. Do you want to install composer? (Y/n)."; run; 
		if [ "$run" == n ]; then
		    exit 1;
		fi
		echo "Downloading composer..."
		curl -sS https://getcomposer.org/installer | php
		echo "Making composer executable..."
		mv composer.phar /usr/local/bin/composer
	}

	#we need npm
	hash npm -v 2>/dev/null || { 
		read -p "I require node's npm but it's not installed. Do you want to install npm? (Y/n)."; run; 
		if [ "$run" == n ]; then
		    exit 1;
		fi
		echo "Downloading npm..."
		curl http://npmjs.org/install.sh | sudo sh
	}
	#we need bower
	hash bower -v 2>/dev/null || { 
		read -p "I require bower but it's not installed. Do you want to install bower? (Y/n)."; run; 
		if [ "$run" == n ]; then
		    exit 1;
		fi
		echo "Downloading bower..."
		npm install -g bower
	}
	
}

check_system_requirements

#############################################################################################
# START INSTALLATION PROCESS
#############################################################################################
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
#TODO: we should ensure that this dirs have the right perms.

THEME_RELEASE=master
THEME="https://github.com/goliatone/flatg-core/archive/${THEME_RELEASE}.tar.gz" 

echo "Downloading flatg theme..."
pushd ./themes
curl -L -O $THEME
# github will redirect this link, you end up having name missmatch
THEME_DIR=$(tar -ztf "${THEME_RELEASE}.tar.gz" | head -n 1)
tar -zxvf "${THEME_RELEASE}.tar.gz" # -C untar_here_dir_name

echo "Cleanup binaries..."
rm "${THEME_RELEASE}.tar.gz"