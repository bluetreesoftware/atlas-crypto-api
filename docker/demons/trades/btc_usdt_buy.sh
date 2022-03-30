#!/bin/sh

sleep 1
php artisan exchange:trade:match-orders --trade-id=1 --action=buy
