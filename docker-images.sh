#!/bin/bash

read -p 'Load(l) , Save(s): ' choice

docker=$(docker image ls --format '{{.Repository}}')
count=0
counts=0
if [[ $choice == 's' || $choice == 'S' ]]; then
for a in $docker 
do 
counts=$((counts + 1))
done
echo $counts

for i in $docker

do 
count=$((count + 1))
echo $count 
echo 'saving: '$i
docker save -o  $i.tar $i
done

fi

if [ $choice == 'l' ]; then
echo 'Loading docker image'
m=$(ls *tar)
for k in m
do    
echo 'Load image: '$k
docker load -i $k
done
fi
