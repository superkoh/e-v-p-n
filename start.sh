#!/bin/sh -e

apt-get -y upgrade
apt-get -y install strongswan strongswan-plugin-*

echo "" > /etc/ipsec.secrets
echo ": PSK \"superkoh\"
koh : XAUTH \"123456\"" >> /etc/ipsec.secrets

echo "" > /etc/ipsec.conf
echo "
config setup
    uniqueids = replace

conn ios   
    keyexchange=ikev1
    left=%defaultroute
    leftauth=psk
    leftsubnet=0.0.0.0/0
    right=%any
    rightauth=psk
    rightauth2=xauth-eap
    rightsourceip=10.7.0.2/21
    rightdns=8.8.8.8
    auto=add
" >> /etc/ipsec.conf

sed -i "/exit 0/d" /etc/rc.local
sed -i "/sysctl net.ipv4.ip_forward=1/d" /etc/rc.local
sed -i "/iptables -t nat -A POSTROUTING -s 10.0.0.0\/8 -o eth0 -j MASQUERADE/d" /etc/rc.local
echo "
sysctl net.ipv4.ip_forward=1
iptables -t nat -A POSTROUTING -s 10.0.0.0/8 -o eth0 -j MASQUERADE
exit 0
" >> /etc/rc.local

sysctl net.ipv4.ip_forward=1
iptables -t nat -A POSTROUTING -s 10.0.0.0/8 -o eth0 -j MASQUERADE

sed -i "s/# accounting = no/accounting = yes/g" /etc/strongswan.d/charon/eap-radius.conf
sed -i "0,/# secret =/ s/# secret =/secret = superkoh/" /etc/strongswan.d/charon/eap-radius.conf
sed -i "s/# server =/server = 123.59.152.121/g" /etc/strongswan.d/charon/eap-radius.conf

service strongswan restart