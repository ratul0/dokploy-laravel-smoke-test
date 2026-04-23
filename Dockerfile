FROM node:24-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json vite.config.js ./
RUN npm ci

COPY resources ./resources
RUN npm run build

FROM dunglas/frankenphp:1-php8.4-bookworm AS runtime

RUN install-php-extensions \
    intl \
    opcache \
    pdo_mysql \
    zip

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist

COPY . .
COPY --from=assets /app/public/build ./public/build
COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY docker/entrypoint.sh /usr/local/bin/dokploy-entrypoint

RUN composer dump-autoload --no-dev --optimize \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x /usr/local/bin/dokploy-entrypoint

EXPOSE 80

ENTRYPOINT ["dokploy-entrypoint"]
