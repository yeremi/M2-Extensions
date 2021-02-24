#!/bin/bash
pgrep httpd
if [ $? -eq 0 ]
then
	/etc/init.d/httpd stop
fi
exit 0
