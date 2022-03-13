#!/bin/bash
set -x

composer install --no-interaction --prefer-dist --optimize-autoloader

cp ./deploy/env/.env.main .env

php artisan migrate:fresh --seed --force

php artisan cache:clear

php artisan route:cache

php artisan config:cache
