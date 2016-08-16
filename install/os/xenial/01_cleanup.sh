#!/usr/bin/env bash

# cleanup system

service apache2 stop
service nginx stop
killall spfd

apt-get -y remove --purge apache2* php* exim4* mysql* dovecot-* clam* nginx* pure-ftpd* pdns* spamassassin mariadb*
apt -y autoremove

rm -Rf /etc/mysql
rm -Rf /var/lib/mysql
rm -Rf /etc/apache2
rm -Rf /etc/nginx
rm -Rf /etc/exim4
rm -Rf /etc/dovecot
rm -Rf /etc/php
rm -Rf /etc/powerdns
rm -Rf /etc/pure-ftpd
