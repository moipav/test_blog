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

# Создаём необходимые папки для кэша Smarty и даём права пользователю www-data
RUN mkdir -p cache/smarty/compile \
    && mkdir -p cache/smarty/cache \
    && mkdir -p cache/smarty/configs \
    && mkdir -p public/uploads \
    && chown -R www-data:www-data cache \
    && chown -R www-data:www-data public/uploads \
    && chmod -R 755 cache \
    && chmod -R 755 public/uploads

# Настройка прав для всей рабочей директории
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]