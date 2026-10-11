FROM php:8.1-fpm-alpine

# Установка расширений
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        intl \
        opcache

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Создаем папки и даем права пользователю www-data (UID 82 в Alpine)
RUN mkdir -p cache/smarty/compile cache/smarty/cache cache/smarty/configs \
    && chown -R www-data:www-data cache

# Настройка прав
RUN chown -R www-data:www-data /var/www/html
EXPOSE 9000
CMD ["php-fpm"]