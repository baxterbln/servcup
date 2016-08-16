<IfModule pagespeed_module>
    ModPagespeed off
    ModPagespeedInheritVHostConfig on
    AddOutputFilterByType MOD_PAGESPEED_OUTPUT_FILTER text/html
    ModPagespeedFileCachePath "/var/cache/mod_pagespeed/"
    ModPagespeedLogDir "/var/log/pagespeed"
    ModPagespeedSslCertDirectory "/etc/ssl/certs"
    ModPagespeedFileCacheInodeLimit 500000
    ModPagespeedStatistics off
    ModPagespeedStatisticsLogging off
    ModPagespeedMessageBufferSize 100000
</IfModule>
