#!/bin/sh
PHPRC="/usr/local/servcup/fcgi"
export PHPRC
export TMPDIR="/usr/local/servcup/tmp"
exec /usr/bin/php-cgi5.6 \
-d upload_tmp_dir="/usr/local/servcup/tmp" \
-d upload_max_filesize=50M \
-d max_execution_time=60 \
-d post_max_size=50M \
-d memory_limit=256M \
-d mysql.allow_persistent=off \
-d safe_mode=off \
-d session.save_path="/usr/local/servcup/tmp"
