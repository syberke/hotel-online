FROM php:8.3-fpm-alpine

# 1. Install ekstensi sistem operasi & NodeJS + NPM versi terbaru di Alpine
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    bash \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_pgsql zip gd

# Ambil Composer resmi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# 2. Jalankan npm install & build MANDIRI di dalam lingkungan kontainer Docker
RUN npm install && npm run build

# Berikan hak akses folder storage & cache ke user www-data bawaan alpine
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]