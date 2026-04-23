#!/bin/sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan optimize

if [ "${DOKPLOY_RUN_DEPLOY_TASKS:-false}" = "true" ]; then
    php artisan migrate --force
    php artisan db:seed --force
fi

exec frankenphp run --config /etc/caddy/Caddyfile
