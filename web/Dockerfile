FROM node:18-alpine AS node
FROM composer:2.3.5

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

WORKDIR /vbrick

COPY . .

RUN mkdir ./bootstrap/cache \
    && mkdir ./storage \
    && mkdir ./storage/logs \
    && mkdir ./storage/framework \
    && mkdir ./storage/framework/cache \
    && mkdir ./storage/framework/sessions \
    && mkdir ./storage/framework/testing \
    && mkdir ./storage/framework/views \
    && mkdir ./storage/app \
    && mkdir ./storage/app/public

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    && composer dump-autoload

RUN npm install && npm run prod

RUN php artisan optimize:clear

CMD php artisan serve --host=0.0.0.0 --port=1024

EXPOSE 1024