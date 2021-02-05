#!/usr/bin/env bash
php artisan migrate --seed 
php-fpm &
service nginx start &
tail -f /var/log/nginx/*log
