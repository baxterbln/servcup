server {
    listen      SERVERIP:80;
    server_name MANGEURL ;

    location / {
        proxy_pass      http://SERVERIP:8080;
        #proxy_cache my-cache;
        #proxy_cache_valid  200 302  60m;
        #proxy_cache_valid  404      1m;
        location ~* ^.+\.(jpeg|jpg|png|gif|bmp|ico|svg|tif|tiff|css|js|htm|html|ttf|otf|webp|woff|txt|csv|rtf|doc|docx|xls|xlsx|ppt|pptx|odf|odp|ods|odt|pdf|psd|ai|eot|eps|ps|zip|tar|tgz|gz|rar|bz2|7z|aac|m4a|mp3|mp4|ogg|wav|wma|3gp|avi|flv|m4v|mkv|mov|mpeg|mpg|wmv|exe|iso|dmg|swf)$ {
            root           /usr/local/servcup/htdocs;
            access_log     /var/log/apache2/domains/MANGEURL.log combined;
            access_log     /var/log/apache2/domains/MANGEURL.bytes bytes;
            expires        max;
            try_files      $uri @fallback;
        }
        # Dont cache these pages.
        # location ~* ^/(wp-admin|wp-login.php)
        # {
        # proxy_pass http://backend;
        # }

    }

    location @fallback {
        proxy_pass      http://SERVERIP:8080;
    }

    #gzip on;
    #gzip_disable msie6;
    #gzip_static on;
    #gzip_comp_level 4;
    #gzip_proxied any;
    #gzip_types text/plain
    #           text/css
    #           application/x-javascript
    #           text/xml
    #           application/xml
    #           application/xml+rss
    #           text/javascript;

    location ~ /\.ht    {return 404;}
    location ~ /\.svn/  {return 404;}
    location ~ /\.git/  {return 404;}
    location ~ /\.hg/   {return 404;}
    location ~ /\.bzr/  {return 404;}
}
