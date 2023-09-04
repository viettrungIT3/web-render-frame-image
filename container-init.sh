#! /usr/bin/env bash

echo "Running container init bash file \n";

if [ ! -d "/var/www/html/uploads" ]
then 
    echo "Create uploads folder \n"
    mkdir /var/www/html/uploads; 
else
    echo "Folder uploads exists \n"
fi;

/usr/sbin/apache2ctl -D FOREGROUND
