#!/bin/sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan optimize

run_deploy_tasks="${DOKPLOY_RUN_DEPLOY_TASKS:-}"

if [ -z "$run_deploy_tasks" ] && [ -f .env ]; then
    run_deploy_tasks="$(grep -E '^DOKPLOY_RUN_DEPLOY_TASKS=' .env | tail -n 1 | cut -d '=' -f 2- | tr -d '\"'\''[:space:]')"
fi

if [ "${run_deploy_tasks:-false}" = "true" ]; then
    php artisan migrate --force
    php artisan db:seed --force
fi

exec frankenphp run --config /etc/caddy/Caddyfile
