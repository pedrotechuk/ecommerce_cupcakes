#!/bin/bash
set -e

php artisan config:clear

php artisan migrate --force
php artisan storage:link || true

# Seeders are idempotent (updateOrCreate/firstOrCreate), except OrderSeeder
# which self-guards against re-running. Safe to run on every boot, so demo
# data fixes ship on the next deploy without needing a manual DB reset.
php artisan db:seed --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

exec supervisord -c /etc/supervisord.conf
