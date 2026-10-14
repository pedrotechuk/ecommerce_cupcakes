#!/bin/bash
set -e

php artisan config:clear

php artisan migrate --force
php artisan storage:link || true

ORDER_COUNT=$(php -r "
require '/var/www/html/vendor/autoload.php';
\$app = require '/var/www/html/bootstrap/app.php';
\$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo \App\Models\Order::count();
")

if [ "$ORDER_COUNT" = "0" ]; then
    echo "Banco vazio, executando seeders de demonstração..."
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

exec supervisord -c /etc/supervisord.conf
