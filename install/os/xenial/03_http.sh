#!/usr/bin/env bash

echo -e "add repositorys ...."

echo "deb http://dl.google.com/linux/mod-pagespeed/deb/ stable main" > /etc/apt/sources.list.d/mod-pagespeed.list
add-apt-repository -y ppa:ondrej/php
apt-key adv --keyserver keyserver.ubuntu.com --recv-keys A040830F7FAC5991
apt-get -qq update

echo -e "setup apache ..."
apt-get -y -qq install apache2 apache2-suexec-custom apache2-utils libapache2-mod-fcgid \
php-pear php5.6 php5.6-cgi php5.6-cli php5.6-curl php5.6-dev php5.6-mbstring \
php5.6-mcrypt php5.6-mysql php5.6-readline php5.6-json

apt-get -y -qq --allow-unauthenticated install mod-pagespeed-stable

mkdir -p ${webfolder}/webs/
mkdir -p ${webfolder}/logs/
mkdir -p ${webfolder}/fcgi/
mkdir -p ${webfolder}/tmp
mkdir -p /etc/apache2/suexec
mkdir -p /var/log/apache2/domains/
chmod 1777 ${webfolder}/tmp
a2dismod userdir
a2enmod suexec deflate expires fcgid rewrite
a2dissite 000-default

echo "${webfolder}\nhtdocs" > /etc/apache2/suexec/www-data
echo "IncludeOptional /usr/local/servcup/conf/apache/*.conf" >> /etc/apache2/apache2.conf

cp templates/ap_ports.tpl /etc/apache2/ports.conf
cp templates/ap_default.tpl /usr/local/servcup/conf/apache/000-default.conf
cp templates/fcgi_default.tpl /usr/local/servcup/fcgi/php5-fcgi
cp templates/php.ini /usr/local/servcup/fcgi/php.ini
chmod 755 /usr/local/servcup/fcgi/php5-fcgi
chmod 755 /usr/local/servcup/fcgi/php.ini

# remove write access
chattr -i /usr/local/servcup/fcgi/php5-fcgi
chattr -i /usr/local/servcup/fcgi/php.ini

replace /usr/local/servcup/conf/apache/000-default.conf MANGEURL $manageUrl

service apache2 restart

#LESEZEICHEN!!!!

echo "setup nginx ..."
apt-get -y install nginx

cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup
cp templates/ng_nginx.tpl /etc/nginx/nginx.conf

cp templates/ng_default.tpl /usr/local/servcup/conf/nginx/000-default.conf
replace /usr/local/servcup/conf/nginx/000-default.conf MANGEURL $manageUrl
replace /usr/local/servcup/conf/nginx/000-default.conf SERVERIP $outip

service nginx restart

cp templates/ap_fcgid.tpl /etc/apache2/mods-enabled/fcgid.conf
service apache2 restart

echo "setup pagespeed"
a2enmod pagespeed
cp templates/ap_pagespeed.tpl /etc/apache2/mods-enabled/pagespeed.conf
service apache2 restart
