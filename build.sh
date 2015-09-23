#!/bin/bash
BUILD=./build
DIST=./dist

if [ ! -d $BUILD ]
	then mkdir $BUILD
fi

if [ ! -d $DIST ]
	then mkdir $DIST
fi

rsync -rlv --exclude-from=./buildignore --delete ./ ./build/

tar czvf $DIST/media-manager.tar.gz --transform=s/build/media-manager/ $BUILD

cd extra/Drupal7
tar czvf mediamanager.tar.gz mediamanager
mv mediamanager.tar.gz ../../dist
