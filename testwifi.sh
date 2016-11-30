#!/bin/bash

TESTIP=192.168.1.1

ping -c4 ${TESTIP} > /dev/null

if [ $? != 1 ]
then
    echo $0 "WiFi seems down, restarting"
    sudo /sbin/ifdown --force wlan0
    sleep 10
    sudo /sbin/ifup wlan0
else
    echo $0 "WiFi seems up."
fi
