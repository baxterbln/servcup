#!/bin/bash

if [ -r /var/spool/exim4/db/greylist.db ]; then
    /usr/bin/sqlite /var/spool/exim4/db/greylist.db <<EOF
.timeout 5000
DELETE FROM greylist WHERE expire < $((`date +%s` - 604800));
EOF
fi
