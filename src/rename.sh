#!/usr/bin/env bash

count=0
for i in *; do
    mv "${i}" pic-${count}.`echo "${i}" | awk -F. '{print $2}'`
    ((count+=2))
done
