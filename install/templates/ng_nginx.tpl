# Server globals
worker_processes        2;
error_log               /var/log/nginx/error.log;
pid                     /var/run/nginx.pid;


# Worker config
events {
        worker_connections  1024;
        use                 epoll;
}


http {
    # Main settings
    sendfile                        on;
    tcp_nopush                      on;
    tcp_nodelay                     on;
    client_header_timeout           1m;
    client_body_timeout             1m;
    client_header_buffer_size       256k;
    client_body_buffer_size         256k;
    client_max_body_size            100m;
    large_client_header_buffers     4   8k;
    send_timeout                    30;
    keepalive_timeout               60 60;
    reset_timedout_connection       on;
    server_tokens                   off;
    server_name_in_redirect         off;
    server_names_hash_max_size      512;
    server_names_hash_bucket_size   512;
    proxy_buffer_size               128k;
    proxy_busy_buffers_size   256k;

    # Log format
    log_format  main    '$remote_addr - $remote_user [$time_local] $request '
                        '"$status" $body_bytes_sent "$http_referer" '
                        '"$http_user_agent" "$http_x_forwarded_for"';
    log_format  bytes   '$body_bytes_sent';
    #access_log          /var/log/nginx/access.log  main;
    access_log off;


    # Mime settings
    include             /etc/nginx/mime.types;
    default_type        application/octet-stream;


    # Compression
    gzip                on;
    gzip_comp_level     9;
    gzip_min_length     512;
    gzip_buffers        8 64k;
    gzip_types          text/plain text/css text/javascript
                        application/x-javascript;
    gzip_proxied        any;

    # Proxy settings
    proxy_redirect      off;
    proxy_set_header    Host            $host;
    proxy_set_header    X-Real-IP       $remote_addr;
    proxy_set_header    X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_pass_header   Set-Cookie;
    proxy_connect_timeout   90;
    proxy_send_timeout  150;
    proxy_read_timeout  150;
    proxy_buffers       32 256k;

    proxy_cache_path /usr/local/servcup/tmp/cache levels=1:2 keys_zone=ServcupCache:10m max_size=10g inactive=60m use_temp_path=off;
    proxy_temp_path /usr/local/servcup/tmp/cache/tmp;

    # SSL PCI Compliance
    ssl_ciphers                 RC4:HIGH:!aNULL:!MD5:!kEDH;
    ssl_session_cache           shared:SSL:10m;
    ssl_prefer_server_ciphers   on;


    # Error pages
    error_page          403          /error/403.html;
    error_page          404          /error/404.html;
    error_page          502 503 504  /error/50x.html;


    # Cache
    proxy_cache_path /var/cache/nginx levels=2 keys_zone=cache:10m inactive=60m max_size=512m;
    proxy_cache_key "$host$request_uri $cookie_user";
    proxy_temp_path  /var/cache/nginx/temp;
    proxy_ignore_headers Expires Cache-Control;
    proxy_cache_use_stale error timeout invalid_header http_502;
    proxy_cache_valid any 3d;

    map $http_cookie $no_cache {
        default 0;
        ~SESS 1;
        ~wordpress_logged_in 1;
    }


    # Wildcard include
    include             /etc/nginx/conf.d/*.conf;
    include             /usr/local/servcup/conf/nginx/*.conf;
}
