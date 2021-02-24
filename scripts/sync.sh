#!/bin/bash
#pwd > /tmp/location

rm -fr /var/www/html/app/code/
rm -fr /var/www/html/app/design/frontend/
rm -fr /var/www/html/app/i18n/

rsync -av --exclude "scripts/" --exclude ".git*" --exclude "appspec.yml" --exclude ".git*" /opt/deploy/ /var/www/html/
chmod 755 /var/www/html/
chmod 755 /var/www/html/bin/magento
