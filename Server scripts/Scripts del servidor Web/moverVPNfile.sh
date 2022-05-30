#!/bin/bash

if [ -f "/var/www/html/user/$1/$1.ovpn" ]
then
    echo "1"
else
    if [ -f "/var/nfs/vpnFiles/$1.ovpn" ]
    then
        cp /var/nfs/vpnFiles/$1.ovpn /var/www/html/user/$1/
        echo "1"
    else
        echo "0"
    fi
fi
