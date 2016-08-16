#!/bin/sh
# 2007-02-22 <pille@struction.de>
#
# to be used after demiming by exim in order to scan
# attachments for potential bad content (still unknown virus)
# that camouflages as something harmless. (e.g. .pdf.exe or .jpg.com)
#
# initial script from http://www.exim.org/eximwiki/ExiscanFilenameBlocking (last edited 2006-12-06 01:33:48 by marca)
# modified to look for double extensions only and keep a copy, if check is positive
#
# DEPENDS ls egrep awk echo tar (with gzip/bzip2) unrar unarj unzip
#
# 2009-10-30  + better support for non-GNU systems by using awk instead of gawk and POSIX-compliant function definitions (thanks to Jesco Freund)

# archive_demime $EXIM_MESSAGE_ID
archive_demime() {
	cp -a ../$1 /tmp/UnknownVirus/ExecuteableDoubleExtensionInArchive/
}

# archive_demime_broken $EXIM_MESSAGE_ID
archive_demime_broken() {
	cp -a ../$1 /tmp/UnknownVirus/CorruptedArchive/
}


#Definicoes
EXTENS='(ad[ep]|asd|ba[st]|chm|cmd|com|cpl|crt|dll|exe|hlp|hta|in[fs]|isp|jse?|jar|lnk|md[bez]|ms[cipt]|ole|ocx|pcd|pif|reg|sc[rt]|sh[sb]|sys|url|vb[es]?|vxd|ws[cfh]|cab)'
#Extensoes atualmente reconhecidas
COMPAC='(zip|rar|arj|tgz|tar|gz|bz2)'
#Previne arquivos compactados dentro de compactados
# 2007-02-21 <pilop@difu.de>  don't catch all executeable extensions, but only those faked double ones.
#EXTENS='[.]('${EXTENS}'|'${COMPAC}')'
EXTENS='[.]...[.]('${EXTENS}'|'${COMPAC}')'
cd /var/spool/exim/scan/$1
#Todos arquivos do arquivo compactado
for i in `ls | egrep -i "${COMPAC}$"`; do
    #arquivos ZIP
    if [ "`echo $i | egrep -i '[.](zip)$'`" != "" ]; then
        #Testar pra ver se o arquivo está OK
        unzip -t $i 2> /dev/null > /dev/null
        if [ ! $? -eq 0 ]; then
	    archive_demime_broken $1
            exit 1
        fi
        #Ver se existe executaveis no conteudo do mesmo
        if [ `zipinfo -1 $i | egrep -i "${EXTENS}$" | wc -l` -gt 0 ]; then
	    archive_demime $1
            exit 1
        fi
    fi
    #arquivos RAR
    if [ "`echo $i | egrep -i '[.](rar)$'`" != "" ]; then
        #Testar pra ver se o arquivo está OK
        unrar t $i 2> /dev/null > /dev/null
        if [ ! $? -eq 0 ]; then
	    archive_demime_broken $1
            exit 1
        fi
        #Ver se existe executaveis no conteudo do mesmo
        if [ `unrar l $i | awk '{ print $1 }' | egrep -i "${EXTENS}$" | wc -l` -gt 0 ]; then
	    archive_demime $1
            exit 1
        fi
    fi
    #arquivos ARJ
    if [ "`echo $i | egrep -i '[.](arj)$'`" != "" ]; then
        #Testar pra ver se o arquivo está OK
        unarj t $i 2> /dev/null > /dev/null
        if [ ! $? -eq 0 ]; then
	    archive_demime_broken $1
            exit 1
        fi
        #Ver se existe executaveis no conteudo do mesmo
        if [ `unarj l $i | awk '{ print $1 }' | egrep -i "${EXTENS}$" | wc -l` -gt 0 ]; then
	    archive_demime $1
            exit 1
        fi
    fi
    #arquivos Tar
    if [ "`echo $i | egrep -i '[.](tar)$'`" != "" ]; then
        if [ `tar --list -f $i | awk '{ print $1 }' | egrep -i "${EXTENS}$" | wc -l` -gt 0 ]; then
	    archive_demime $1
            exit 1
        fi
    fi
    #arquivos TGZ e Tar.GZ
    if [ "`echo $i | egrep -i '[.](tgz|gz)$'`" != "" ]; then
        if [ `tar --list -zf $i | awk '{ print $1 }' | egrep -i "${EXTENS}$" | wc -l` -gt 0 ]; then
	    archive_demime $1
            exit 1
        fi
    fi
    #arquivos tar.bz2
    if [ "`echo $i | egrep -i '[.](bz2)$'`" != "" ]; then
        if [ `tar --list -jf $i | awk '{ print $1 }' | egrep -i "${EXTENS}$" | wc -l` -gt 0 ]; then
	    archive_demime $1
            exit 1
        fi
    fi
done
exit 0
