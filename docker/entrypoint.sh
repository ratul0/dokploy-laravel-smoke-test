#!/bin/sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan optimize

exec frankenphp run --config /etc/caddy/Caddyfile
