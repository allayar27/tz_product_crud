#docker/php/Dcckerfile

FROM php:8.2-fpm as php

WORKDIR /var/www/app

# Устанавливаем необходимые системные зависимости
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    build-essential \
    locales \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    nano \
    supervisor\
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p "/etc/supervisor/logs"
# Скопируйте конфигурации
COPY ./docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# COPY ./docker/entrypoint.local.sh /docker/entrypoint.local.sh
COPY ./docker/entrypoint.sh /docker/entrypoint.sh
# RUN chmod +x /docker/entrypoint.local.sh
RUN chmod +x /docker/entrypoint.sh

COPY . /var/www/app

RUN touch /var/log/php-fpm.log

# Скопируйте конфигурационный фаилы.
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
# COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf


COPY --chown=www-data:www-data . .

RUN mkdir -p ./storage/framework
RUN mkdir -p ./storage/framework/{cache, testing, sessions, views}
RUN mkdir -p ./storage/framework/bootstrap
RUN mkdir -p ./storage/framework/bootstrap/cache

# Настройка прав доступа
RUN usermod --uid 1000 www-data
RUN groupmod --gid 1000 www-data

# ENTRYPOINT ["/docker/entrypoint.local.sh"]
ENTRYPOINT ["/docker/entrypoint.sh"]
