#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive

echo -e "\nWARNING!!!!"
echo -e "This installer delete all exist packages for a clean install\n"
echo -e "\nWARNING!!!!"

echo -e "\nPlease enter the IP address for the external interface"
echo -e "Be certain that you entered it correctly before proceeding"
echo -n
read outip

validateIp $outip

if [[ ( $outip == "" ) || ( $? -ne 0 ) ]]; then
    echo -e "no ip, no install. Bye!"
    exit 0
fi

echo -e "\nPlease enter the hostname (without http://) for the manage interface"
echo -n
read manageUrl

hostnameMatch=`echo $manageUrl | grep -P '(?=^.{5,254}$)(^(?:(?!\d+\.)[a-zA-Z0-9_\-]{1,63}\.?)+(?:[a-zA-Z]{2,})$)'`
if [[ ( $manageUrl == "" ) || ( $manageUrl != $hostnameMatch ) ]]; then
    echo -e "no hostname, no install. Bye, Bye!"
    exit 0
fi

echo -e "\nPlease enter the default folder (/var/clients is default) for websites"
echo -n
read webfolder

if [[ $webfolder == "" ]]; then
    webfolder="/var/clients"
    echo -e "use /var/clients"
fi

echo -e "\nPlease enter the default folder (/var/mail is default) for mails"
echo -n
read mailfolder

if [[ $mailfolder == "" ]]; then
    mailfolder="/var/mail"
    echo -e "use /var/mail\n"
fi

if [[ $fqdn == "" ]]; then
    fqdn=$manageUrl
fi
