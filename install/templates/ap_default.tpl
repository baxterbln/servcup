<Directory /var/clients>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

<IfModule mod_headers.c>
    <LocationMatch "/.well-known/acme-challenge/*">
        Header set Content-Type "text/plain"
    </LocationMatch>
</IfModule>

Alias /phpMyAdmin/ "/usr/local/servcup/phpmyadmin/"
<Directory "/usr/local/servcup/phpmyadmin/">
    Options +ExecCGI
    Order allow,deny
    Allow from all
    Require all granted
    AddHandler fcgid-script .php
    FCGIWrapper /usr/local/servcup/fcgi/php5-fcgi .php
</Directory>

<Directory "/usr/local/servcup/conf/letsencrypt/.well-known/acme-challenge">
    SetHandler none
    AllowOverride None
    Order allow,deny
    Allow from all
    Require all granted
</Directory>

<VirtualHost *:8080>
    ServerName MANGEURL
    DocumentRoot /usr/local/servcup/htdocs
    CustomLog /var/log/apache2/domains/MANGEURL.bytes bytes
    CustomLog /var/log/apache2/domains/MANGEURL.log combined
    ErrorLog /var/log/apache2/domains/MANGEURL.error.log

    AddHandler fcgid-script .php
    <Directory "/usr/local/servcup/htdocs">
       AllowOverride All
       Options -Indexes -MultiViews +FollowSymLinks +ExecCGI +Includes
       FCGIWrapper /usr/local/servcup/fcgi/php5-fcgi .php
       Order allow,deny
       allow from all
       Require all granted
    </Directory>

</VirtualHost>
