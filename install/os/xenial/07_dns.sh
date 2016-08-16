#!/usr/bin/env bash

echo "setup powerdns"

apt-get install -y -qq pdns-server pdns-backend-mysql
rm -Rf /etc/powerdns/pdns.d/*

DNSPASS=$(randpw)

mysql -uroot -p${MYSQLPW} -e "CREATE DATABASE servdns /*\!40100 DEFAULT CHARACTER SET utf8 */;"

mysql -uroot -p${MYSQLPW} servdns < templates/sql/structure_pdns.sql

mysql -uroot -p${MYSQLPW} -e "CREATE USER servdns@localhost IDENTIFIED BY '${DNSPASS}';"
mysql -uroot -p${MYSQLPW} -e "GRANT ALL PRIVILEGES ON servdns.* TO 'servdns'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "FLUSH PRIVILEGES;"

mysql -uroot -p${MYSQLPW} -e "DROP DATABASE IF EXISTS pdns;"
mysql -uroot -p${MYSQLPW} -e "DROP USER IF EXISTS 'pdns'@'localhost';"

replace /usr/local/servcup/htdocs/application/config/database.php MYSQLDNSPW $DNSPASS

cat <<EOF > /etc/powerdns/pdns.d/pdns.local.gmysql.conf
# MySQL Configuration file

launch=gmysql

gmysql-host=localhost
gmysql-dbname=servdns
gmysql-user=servdns
gmysql-password=${DNSPASS}
gmysql-dnssec=no
recursor=127.0.0.1:8699
EOF

echo "local-port=8699" >> /etc/powerdns/recursor.conf
