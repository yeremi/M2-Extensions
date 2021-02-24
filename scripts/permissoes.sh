#!/bin/bash
set -x
cd /var/www/html
chmod -Rf 755 /var/www/html
chown -Rf apache:apache /var/www/html/
chmod -Rf gu+rw var/ pub/static/ pub/media/ app/etc/
chmod -Rf gu+r vendor/ lib/
chmod  gu+x bin/magento