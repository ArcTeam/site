#!/usr/bin/env bash
rsync -varzhP --delete -e "ssh -p 7070" /var/www/html/atw/ beppe@naporezza.asuscomm.com:/var/www/html/atw/
