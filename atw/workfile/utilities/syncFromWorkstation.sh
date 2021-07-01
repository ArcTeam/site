#!/usr/bin/env bash
rsync -varzhP --delete -e "ssh -p 7070" beppe@naporezza.asuscomm.com:/var/www/html/atw/ /var/www/html/atw/
