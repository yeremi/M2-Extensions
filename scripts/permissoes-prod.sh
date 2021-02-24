#!/bin/bash
set -x
cd /var/www/html/
chmod 755 /var/www/html/
find ./pub/media ! -user apache -exec chown apache:root '{}' \; > /dev/null 2>&1 &
chown apache:root -R var vendor pub/static
chmod -R u+w pub/media/ > /dev/null 2>&1 &
chmod -R u+w  var/ vendor/ pub/static/ app/etc/
chmod  u+x bin/magento
chmod  g+x bin/magento