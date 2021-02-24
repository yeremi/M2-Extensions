#!/bin/bash
if [ -f /var/www/html/composer.json ]
then
         cp -f /var/www/html/composer.json /tmp/composer.json
fi
exit 0
