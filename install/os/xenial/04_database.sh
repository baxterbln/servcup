#!/usr/bin/env bash

echo "setup mysql and databases"

MYSQLPW=$(randpw)
SERVCUPPW=$(randpw)
ADMINPW=$(randpw)
PMAPASS=$(randpw)

apt-get -qq -y install mysql-server

mysql -uroot -p${MYSQLPW} mysql -e "UPDATE user SET authentication_string=PASSWORD('${MYSQLPW}'), plugin='mysql_native_password' WHERE user='root';"
mysql -uroot -p${MYSQLPW} -e "FLUSH PRIVILEGES;"

# Create dafault user
mysql -uroot -p${MYSQLPW} -e "CREATE DATABASE servcup /*\!40100 DEFAULT CHARACTER SET utf8 */;"
mysql -uroot -p${MYSQLPW} -e "CREATE USER servcup@localhost IDENTIFIED BY '${SERVCUPPW}';"
mysql -uroot -p${MYSQLPW} -e "GRANT ALL PRIVILEGES ON servcup.* TO 'servcup'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "FLUSH PRIVILEGES;"

mysql -uroot -p${MYSQLPW} servcup < templates/sql/structure.sql

cp templates/if_database.tpl /usr/local/servcup/htdocs/application/config/database.php
replace /usr/local/servcup/htdocs/application/config/database.php MYSQLPW $SERVCUPPW
replace /usr/local/servcup/htdocs/application/config/database.php MYSQLROOTPW $MYSQLPW

mysql -uroot -p${MYSQLPW} servcup << EOF
UPDATE settings SET value="${webfolder}" WHERE \`key\`="client_path";
INSERT INTO user SET customer_id="1", group_id="1", username="admin", password=md5("${ADMINPW}"), active="1";
EOF

mysql -uroot -p${MYSQLPW} < src/phpmyadmin/sql/create_tables.sql

mysql -uroot -p${MYSQLPW} -e "CREATE USER phpmyadmin@localhost IDENTIFIED BY '${PMAPASS}';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, INSERT, DELETE, UPDATE, ALTER ON \`phpmyadmin\`.* TO 'phpmyadmin'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "FLUSH PRIVILEGES;"

replace /usr/local/servcup/phpmyadmin/config.inc.php PMAPASS $PMAPASS
chmod 755 -R /usr/local/servcup/phpmyadmin

echo "sql_mode = STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" >> /etc/mysql/mysql.conf.d/mysqld.cnf
