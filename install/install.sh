#!/usr/bin/env bash
########
echo starting postinst...
########

randpw() {
    echo `tr -cd 0-9A-Za-z </dev/urandom | head -c 8`
}

replace()  {
    file=$1
    search=$2
    replace=$3

    cp $file $file.old
    sed "s/$search/$replace/g" $file.old > $file
    rm $file.old
}

function validateIp()
{
    local  ip=$1
    local  stat=1

    if [[ $ip =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
        OIFS=$IFS
        IFS='.'
        ip=($ip)
        IFS=$OIFS
        [[ ${ip[0]} -le 255 && ${ip[1]} -le 255 \
            && ${ip[2]} -le 255 && ${ip[3]} -le 255 ]]
        stat=$?
    fi
    return $stat
}

DISTRIB=""

if [ -f /etc/lsb-release ]; then

    . /etc/lsb-release
    if [[ $DISTRIB_CODENAME == "xenial" ]]; then
        DISTRIB="xenial"
    elif [[ $DISTRIB_CODENAME == "xenial" ]]; then
        DISTRIB=""
    else
        echo "not supported os, sorry"
        exit 0
    fi

elif [ -f /etc/redhat-release ]; then
    DISTRIB=`cat /etc/redhat-release`
else
    echo "not supported os, sorry"
    exit 0
fi

echo -e "Please enter your installation methode\n"
PS3='Please enter your choice: '
options=("Full Server" "Webserver" "Mailserver" "DNS Server" "Quit")
select opt in "${options[@]}"
do
    case $opt in
        "Full Server")
            echo "you chose choice 1"
            . ./os/${DISTRIB}/00_init.sh
            . ./os/${DISTRIB}/01_cleanup.sh
            . ./os/${DISTRIB}/02_base.sh
            . ./os/${DISTRIB}/03_http.sh
            . ./os/${DISTRIB}/04_database.sh
            . ./os/${DISTRIB}/05_mail.sh
            . ./os/${DISTRIB}/06_ftp.sh
            . ./os/${DISTRIB}/07_dns.sh
            . ./os/${DISTRIB}/08_finish.sh
            exit 0
            ;;
        "Webserver")
            echo "not supported yet"
            ;;
        "Mailserver")
            echo "not supported yet"
            ;;
        "DNS Server")
            echo "not supported yet"
            ;;
        "Quit")
            break
            ;;
        *) echo invalid option;;
    esac
done
