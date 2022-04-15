FROM php:8.0-cli-alpine

WORKDIR /usr/src/mockipho

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache --update ${PHPIZE_DEPS} \
    && pecl install xdebug-3.1.0 \
    && docker-php-ext-enable xdebug \
    && apk add --no-cache --virtual .persistent-deps libffi-dev \
    && docker-php-ext-configure ffi --with-ffi \
    && docker-php-ext-install ffi \
    && apk del ${PHPIZE_DEPS}
