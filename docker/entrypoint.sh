#!/bin/bash
set -e

echo "Aguardando o MySQL em ${DB_HOST}:${DB_PORT}..."
until mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --skip-ssl --silent 2>/dev/null; do
    sleep 2
done
echo "MySQL disponível."

if [ ! -d vendor ]; then
    composer install --no-interaction --optimize-autoloader
fi

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q "^APP_KEY=base64" .env; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan storage:link || true

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
