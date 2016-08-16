#!/usr/bin/env bash

echo "installing interface..."

rm -Rf /usr/local/servcup
mkdir -p /usr/local/servcup

cp -Rpa ../htdocs /usr/local/servcup/.

mkdir -p /usr/local/servcup/conf
mkdir -p /usr/local/servcup/conf/server
mkdir -p /usr/local/servcup/fcgi
mkdir -p /usr/local/servcup/tmp
mkdir -p /usr/local/servcup/tmp/cache
mkdir -p /usr/local/servcup/conf/apache
mkdir -p /usr/local/servcup/conf/nginx
mkdir -p /usr/local/servcup/conf/letsencrypt
mkdir -p /usr/local/servcup/conf/letsencrypt/_account
mkdir -p /usr/local/servcup/conf/letsencrypt/.well-known/acme-challenge

chmod 777 /usr/local/servcup/conf/letsencrypt/_account
chmod 777 /usr/local/servcup/conf/letsencrypt/.well-known/acme-challenge

chmod -R 755 /usr/local/servcup/htdocs

chown www-data. /usr/local/servcup/tmp
cp -Rpa src/phpmyadmin /usr/local/servcup/phpmyadmin
chown -R www-data /usr/local/servcup/htdocs/assets/js/locale
mkdir -p /usr/local/servcup/tmp/cache
chmod -R 1770 /usr/local/servcup/tmp



echo "installing packages ..."
