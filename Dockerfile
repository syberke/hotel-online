FROM php:8.3-fpm-alpine

# Install ekstensi sistem operasi Alpine & driver PostgreSQL untuk Laravel
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo pdo_pgsql zip gd

# Ambil Composer resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Berikan hak akses folder storage & cache ke user www-data bawaan alpine
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]