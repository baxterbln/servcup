#!/usr/bin/env bash

echo "setup pure-ftpd"
apt-get -qq -y  install pure-ftpd-mysql

groupadd -g 2001 ftpgroup
useradd -u 2001 -s /bin/false -d /bin/null -c "pureftpd user" -g ftpgroup ftpuser

FTPPASS=$(randpw)

mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, UPDATE ON servcup.ftpd TO 'pureftpd'@'localhost' IDENTIFIED BY '${FTPPASS}';"
mysql -uroot -p${MYSQLPW} -e "FLUSH PRIVILEGES;"

cp /etc/pure-ftpd/db/mysql.conf /etc/pure-ftpd/db/mysql.conf_orig

cat <<EOF > /etc/pure-ftpd/db/mysql.conf
MYSQLServer     localhost
#MYSQLPort       3306
MYSQLUser       pureftpd
MYSQLPassword   ${FTPPASS}
MYSQLDatabase   servcup
MYSQLCrypt      md5
MYSQLGetPW      SELECT password FROM ftpd WHERE user="\L" AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MYSQLGetUID     SELECT uid FROM ftpd WHERE user="\L" AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MYSQLGetGID     SELECT gid FROM ftpd WHERE user="\L"AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MYSQLGetDir     SELECT dir FROM ftpd WHERE user="\L"AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MySQLGetBandwidthUL SELECT upload FROM ftpd WHERE user="\L"AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MySQLGetBandwidthDL SELECT download FROM ftpd WHERE user="\L"AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MySQLGetQTASZ   SELECT quotasize FROM ftpd WHERE user="\L"AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
MySQLGetQTAFS   SELECT quotafiles FROM ftpd WHERE user="\L"AND status="1" AND (ipaccess = "*" OR ipaccess LIKE "\R")
EOF

echo "yes" > /etc/pure-ftpd/conf/ChrootEveryone
echo "yes" > /etc/pure-ftpd/conf/CreateHomeDir
echo "yes" > /etc/pure-ftpd/conf/DontResolve

service pure-ftpd-mysql restart
