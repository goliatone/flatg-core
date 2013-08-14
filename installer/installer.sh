#!/bin/bash
FLATG_RELEASE=v0.0.0
FLAT_TAR="https://github.com/goliatone/flatg-core/archive/${FLATG_RELEASE}.tar.gz" 
TMP_DIR=/tmp/.flatg_install
APACHE_USER=$(ps axho pid,user,comm|grep -E "httpd|apache"|uniq|grep -v "root"| sort -nrk1 |awk 'END {if ($1) print $2}')

default="./flatg"
read -p "Enter installation directory [$default]: " ROOT
ROOT=${ROOT:-$default}
echo "Root is $ROOT"

# echo "Run install? (Y/n)"
# read -e run

# -z checks if its empty string
[ -z "$ROOT" ] && echo "You did not providE a path"
# if [ -z "$BLOGNAME"]; then
# 	echo "You did not provid a path"
# fi

if [ "$run" == n ]; then
    exit
fi

check_system_requirements(){
	hash composer 2>/dev/null || { echo >&2 "I require foo but it's not installed.  Aborting."; exit 1; }
}

check_system_requirements

echo "Creating tmp dir..."
mkdir -p $TMP_DIR

echo "Creating flatg dir structure at $ROOT..."
mkdir -p ${ROOT}/{assets,articles,system}
echo "TODO: Assign right permissions to directories..."
echo "TODO: Assign user to own created files..."


echo "Downloading flatg binaries..."
echo $FLAT_TAR
pushd $TMP_DIR
curl -L -O $FLAT_TAR
echo "Uncompressing binaries...${FLATG_RELEASE}.tar.gz"
mkdir flatg
# github will redirect this link, you end up having name missmatch
TARDIR=$(tar -ztf "${FLATG_RELEASE}.tar.gz" | head -n 1)
tar -zxvf "${FLATG_RELEASE}.tar.gz" -C  flatg
# go to untarred dir
pushd flatg/${TARDIR}
# copy files to parent dir
cp -rf . ..
# move back to parent dir
popd
#remove files from wordpress folder
rm -R flatg/${TARDIR}
# remove tar file
rm "${FLATG_RELEASE}.tar.gz"

method(){
    echo "Something goes here $1"
    echo "or there $2"
}

method "something" "goes"