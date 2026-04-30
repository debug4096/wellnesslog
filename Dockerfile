FROM php:8.4-fpm-alpine AS base

ARG UID=1000
ARG GID=1000
ARG USER_NAME=wellness

ENV COMPOSER_ALLOW_SUPERUSER=0 \
    COMPOSER_NO_INTERACTION=1

WORKDIR /var/www/html

RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        linux-headers \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        g++ \
        make \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        opcache \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && rm -rf /tmp/pear

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

RUN addgroup -g ${GID} -S ${USER_NAME} \
    && adduser -u ${UID} -S -G ${USER_NAME} ${USER_NAME}

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-custom.ini


FROM base AS dev

RUN apk add --no-cache git bash \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps \
    && rm -rf /tmp/pear

COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/zz-xdebug.ini

USER ${USER_NAME}

CMD ["php-fpm"]


FROM base AS prod

COPY --chown=${USER_NAME}:${USER_NAME} composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --prefer-dist \
    --no-scripts \
    --no-progress

COPY --chown=${USER_NAME}:${USER_NAME} . .

RUN composer dump-autoload --optimize --no-dev \
    && chmod -R 775 storage bootstrap/cache

USER ${USER_NAME}

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD php -r "exit(file_get_contents('http://127.0.0.1:9000/ping') === 'pong' ? 0 : 1);" || exit 1

CMD ["php-fpm"]
