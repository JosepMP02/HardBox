#!/bin/bash

#Admitir trafico de TUN (vpn) a ETH1 (Red Interna)
iptables -A FORWARD -i eth1 -o tun0 -j ACCEPT -m comment --comment "VPN FORWARD Allow ETH1 --> TUN0"
iptables -A FORWARD -i tun0 -o eth1 -j ACCEPT -m comment --comment "VPN FORWARD Allow TUN0 --> ETH1"

#Bloquear trafico de TUN (vpn) a ETH0 (Red de los servers)
iptables -A FORWARD -i tun0 -o eth0 -d 192.168.1.2 -j DROP -m comment --comment "Deny vpn to server 192.168.1.2"
iptables -A FORWARD -i tun0 -o eth0 -d 192.168.1.3 -j DROP -m comment --comment "Deny vpn to server 192.168.1.3"
iptables -A FORWARD -i tun0 -o eth0 -d 192.168.1.10 -j DROP -m comment --comment "Deny vpn to server 192.168.1.10"
iptables -A FORWARD -i tun0 -o eth0 -d 192.168.1.1 -m multiport --dport 80,443 -j DROP -m comment --comment "Deny vpn to server 192.168.1.1 por http"

#Mas reglas pa que funcione el forward de la VPN a la red interna
iptables -A FORWARD -i eth1 -o tun0 -m state --state RELATED,ESTABLISHED -j ACCEPT -m comment --comment "vpn lan"
iptables -A FORWARD -i tun0 -o eth1 -m state --state RELATED,ESTABLISHED -j ACCEPT -m comment --comment "vpn lan"

iptables -t nat -A POSTROUTING -o tun0 -j MASQUERADE -m comment --comment "vpn lan"
iptables -t nat -A POSTROUTING -o eth1 -j MASQUERADE -m comment --comment "vpn lan"

iptables -nL
iptables -nL -t nat