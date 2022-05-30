#!/bin/bash

log () {
    echo $1 >> /home/josep/requests_log.txt
}

vpnUser () {
    /home/ahnuar/createuser.sh $1
}



while [ True ]
do
    req=$(nc -nvlp 3030)

    reqType=$(echo $req | cut -d "," -f 1)
    reqData=$(echo $req | cut -d "," -f 2)

    log $req

    if [[ $reqType == 'newvpn' ]]
    then
        vpnUser $reqData
    fi
done
