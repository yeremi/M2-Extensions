#!/bin/bash
set -e
cd /var/www/html
#enter maintenance
bin/magento maintenance:enable
sudo rm -rf var/generation/* var/di/* var/view_preprocessed/* var/page_cache/* var/cache/* pub/static/*
if [ -e  /opt/newstart.txt ]
then
	export HOME=/root
	composer update --no-dev
	npm install
  npm update
	rm -fv /opt/newstart.txt
fi

if [ -f /tmp/composer.json ]
then
    set +e
    diff /tmp/composer.json /var/www/html/composer.json
    if [ $? -eq 1 ]
        then
          set -e
          export COMPOSER_HOME=/root/.composer/
          composer update --no-dev
    fi
fi
set -e
chmod gu+x /var/www/html/bin/magento
bin/magento setup:upgrade
bin/magento setup:di:compile
#grunt clean:klloom
#grunt exec:klloom
#grunt less:klloom
bin/magento cache:flush
bin/magento cache:clean
bin/magento setup:static-content:deploy -f -j 1 --exclude-theme Magento/blank --exclude-theme Magento/luma en_US
bin/magento indexer:reindex
bin/magento cache:flush
bin/magento cache:clean
# disable maintenance mode
bin/magento maintenance:disable