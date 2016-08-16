#!/usr/bin/env bash

echo "setup Mailserver"

MAILPW=$(randpw)  # Mail password

apt-get -y -qq install exim4 exim4-base \
exim4-config exim4-daemon-heavy dovecot-core dovecot-imapd dovecot-mysql dovecot-pop3d \
dovecot-sieve clamav-daemon clamav-freshclam openssl spamassassin libnet-cidr-lite-perl \
sqlite mailman

uid=`id -u Debian-exim`
gid=`id -g Debian-exim`

cp templates/sql/structure_mail.sql /tmp/structure_mail.sql
replace /tmp/structure_mail.sql MUID $uid
replace /tmp/structure_mail.sql MGID $gid

mysql -uroot -p${MYSQLPW} servcup < /tmp/structure_mail.sql

mysql -uroot -p${MYSQLPW} -e "CREATE USER servmail@localhost IDENTIFIED BY '${MAILPW}';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_domains TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_users TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_blocklists TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_domainalias TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_groups TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_group_contents TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "GRANT SELECT, REFERENCES ON servcup.mail_ml TO 'servmail'@'localhost';"
mysql -uroot -p${MYSQLPW} -e "FLUSH PRIVILEGES;"

mysql -uroot -p${MYSQLPW} servcup << EOF
UPDATE settings SET value="${mailfolder}" WHERE \`key\`="mail_path";
EOF

rm -Rf /etc/dovecot/*
rm -Rf /etc/exim4/*
cp -r templates/mail/etc/* /etc/.
chmod 755 /etc/exim4/*

echo "Please enter your country code (DE, ES, US for example)"
echo -n :
read countryCode

echo "Please enter your country (Germany, Spain for example)"
echo -n :
read country

echo "Please enter your city"
echo -n :
read city

echo "Please enter your organisation name"
echo -n :
read organisation

openssl req -x509 -nodes -newkey rsa:2048 -keyout /etc/exim4/exim.key -out /etc/exim4/exim.csr -subj "/C=${countryCode}/ST=${country}/L=${city}/O=${organisation}/OU=/CN=${fqdn}" -days 9000

mkdir -p /usr/local/servcup/conf/db
chown www-data. /usr/local/servcup/conf/db
chmod 777 /usr/local/servcup/conf/db

mkdir $mailfolder/sieve -p
cp templates/mail/spam-global.sieve $mailfolder/sieve/spam-global.sieve
chown -R Debian-exim:Debian-exim $mailfolder

replace /etc/exim4/servcup_local.conf.inc MAILPW $MAILPW
replace /etc/exim4/servcup_local.conf.inc FQDN $fqdn
replace /etc/exim4/exim4.conf MAILFOLDER $mailfolder

ipaddr=`ifconfig 2>/dev/null|awk '/inet addr:/ {print $2}'|sed 's/addr://'`
interfaces=$(echo $ipaddr | sed 's/127.0.0.1//g' | sed 's/\s+/\n/g')
echo $interfaces >> /etc/exim4/servcup_hostnames+hostIPs
echo $fqdn >> /etc/exim4/servcup_hostnames+hostIPs

mkdir /var/spool/exim4/db -p
sqlite /var/spool/exim4/db/greylist.db < templates/mail/xtrasw/exim-greylist/mk-greylist-db.sql
chown -R Debian-exim:Debian-exim /var/spool/exim4/db

cp templates/mail/xtrasw/exim-greylist/greylist-tidy.sh /etc/cron.daily/.

cd src
gzip -dc Mail-SPF-Query-1.999.1.tar.gz | tar -xof -
cd Mail-SPF-Query-1.999.1
perl Makefile.PL
make
make install
cd ../../

echo "/usr/local/bin/spfd -path=/tmp/spfd --socket-user Debian-exim --socket-group Debian-exim --set-user Debian-exim &" >> /etc/rc.local
/usr/local/bin/spfd -path=/tmp/spfd --socket-user Debian-exim --socket-group Debian-exim --set-user Debian-exim &

cp templates/mail/xtrasw/exim-tidydb/exim-tidydb /etc/cron.daily/.

replace /etc/dovecot/dovecot-sql.conf MAILPW $MAILPW
replace /etc/dovecot/dovecot-sql.conf MUID $uid
replace /etc/dovecot/dovecot-sql.conf MGID $gid

replace /etc/dovecot/dovecot.conf FQDN $fqdn
replace /etc/dovecot/dovecot.conf MAILFOLDER $mailfolder

usermod -a -G Debian-exim clamav

if [ -e /etc/initd.d/apparmor ]; then
cp templates/mail/usr.sbin.clamd /etc/apparmor.d/usr.sbin.clamd
/etc/initd.d/apparmor restart
fi

cp templates/mail/spamassassin.tpl /etc/default/spamassassin
service spamassassin restart

echo -e "update clamav, please wait...."
freshclam

echo -e "restart services"
service clamav-daemon restart
service exim4 restart
service dovcot restart
