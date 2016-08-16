<IfModule mod_fcgid.c>
    FcgidConnectTimeout 300
    <IfModule mod_mime.c>
        AddHandler fcgid-script .fcgi
    </IfModule>
</IfModule>
