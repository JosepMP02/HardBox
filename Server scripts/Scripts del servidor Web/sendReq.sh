#!/bin/bash


if [[ $1 == 'vpn' ]]
then
    echo 'newvpn,'$2 > /dev/tcp/192.168.1.30/3030
elif [[ $1 == '--help' ]]
then
    echo "./sendReq.sh [tipo] [dato]"
    echo "Ejemplo:"
    echo "./sendReq.sh [newvpn] [usuarioVPN]"
fi
