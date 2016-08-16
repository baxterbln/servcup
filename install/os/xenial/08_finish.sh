MYSQL_SERVER="localhost"
MYSQL_USER="root"

APP_SERVER="localhost"
APP_USER="servcup"

MAIL_SERVER="localhost"
MAIL_USER="servmail"

FTP_SERVER="localhost"
FTP_USER="pureftpd"

DNS_SERVER="localhost"
DNS_USER="servdns"

## Create global config files
CONFIG_MYSQL="{\"server\":\""$MYSQL_SERVER"\", \"user\":\""$MYSQL_USER"\", \"password\":\""$MYSQLPW"\"}"
CONFIG_APP="{\"server\":\""$APP_SERVER"\", \"user\":\""$APP_USER"\", \"password\":\""$SERVCUPPW"\"}"
CONFIG_MAIL="{\"server\":\""$MAIL_SERVER"\", \"user\":\""$MAIL_USER"\", \"password\":\""$MAILPW"\"}"
CONFIG_FTP="{\"server\":\""$FTP_SERVER"\", \"user\":\""$FTP_USER"\", \"password\":\""$FTPPASS"\"}"
CONFIG_DNS="{\"server\":\""$DNS_SERVER"\", \"user\":\""$DNS_USER"\", \"password\":\""$DNSPASS"\"}"
CONFIG_PMA="{\"server\":\""localhost"\", \"user\":\""phpmyadmin"\", \"password\":\""$PMAPASS"\"}"

cat <<EOF > /usr/local/servcup/conf/server/mysql.json
$CONFIG_MYSQL
EOF

cat <<EOF > /usr/local/servcup/conf/server/app.json
$CONFIG_APP
EOF

cat <<EOF > /usr/local/servcup/conf/server/mail.json
$CONFIG_MAIL
EOF

cat <<EOF > /usr/local/servcup/conf/server/ftp.json
$CONFIG_FTP
EOF

cat <<EOF > /usr/local/servcup/conf/server/dns.json
$CONFIG_DNS
EOF

cat <<EOF > /usr/local/servcup/conf/server/pma.json
$CONFIG_PMA
EOF
chmod 700 -R /usr/local/servcup/conf/server

service pdns-recursor restart
service pdns restart
service mysql restart
service apache2 restart
service nginx restart
service exim4 restart
service dovecot restart
service pure-ftpd-mysql restart

echo -e "\n\n Server Installation compete... have a lot of fun."

echo -e "#Mysql Root PW:        $MYSQLPW\t\t#"
echo -e "#ServCup URL:          http://$manageUrl\t\t#"
echo -e "#ServCup Username:     admin\t\t#"
echo -e "#ServCup Password:     $ADMINPW\t\t#"
