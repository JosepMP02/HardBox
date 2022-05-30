#!/bin/bash

if [ -f "/var/www/html/user/$1/$1.ovpn" ]
then
    rm /var/www/html/user/$1/$1.ovpn
fi
