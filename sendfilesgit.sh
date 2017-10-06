#!/bin/bash -x

now=`date +%d/%m/%Y_%H:%M:%S`

#cd hms/
git add --verbose .
git commit -m "commited at $now"
git push -u gogs master
#git push -u github master
