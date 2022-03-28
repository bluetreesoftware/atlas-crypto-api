#!/bin/sh

sleep 3
php artisan queue:work --queue=transactions_w2w --tries=1
