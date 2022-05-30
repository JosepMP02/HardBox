#!/bin/sh
FILE=minime.sql.`date +"%Y%m%d"`
DBSERVER=127.0.0.1
DATABASE="webapp"
USER="root"
PASS=""

unalias rm     2> /dev/null
rm ${FILE}     2> /dev/null
rm ${FILE}.gz  2> /dev/null

mysqldump --opt --user=${USER} --password=${PASS} ${DATABASE} > ${FILE}

gzip $FILE

echo "${FILE}.gz se creo correctamente:"
ls -l ${FILE}.gz
