# Tutorial Deployment Docker Oasis Hotel di Ubuntu

Dokumen ini menjelaskan deployment **secara manual dari nol**, termasuk fungsi dan isi
setiap file Docker. Setelah memahami alurnya, bagian terakhir menyediakan cara otomatis
dengan `deploy.sh`.

Hasil akhir deployment:

- satu container MariaDB 11.4;
- tiga container Laravel + PHP 8.3 + Apache (`web1`, `web2`, `web3`);
- satu container Nginx sebagai load balancer pada port `8080`;
- satu network internal untuk komunikasi antar-container;
- volume persisten untuk database dan file upload;
- restart policy agar container hidup kembali otomatis;
- identitas berbeda pada setiap web node untuk membuktikan load balancing.

## 1. Arsitektur yang akan dibuat

```text
Browser
   |
   | http://IP_SERVER:8080
   v
Nginx loadbalancer
   |----------|----------|
   v          v          v
 web1       web2       web3
   |----------|----------|
              v
          MariaDB
```

Hanya Nginx yang membuka port ke host. Web node dan database berkomunikasi melalui
network `hotel_internal` dan tidak diekspos langsung ke internet.

## 2. Prasyarat Ubuntu

Pastikan server memiliki Docker Engine, Docker Compose v2, Git, OpenSSL, dan `curl`.
Ikuti instalasi Docker resmi untuk Ubuntu jika Docker belum tersedia:

- <https://docs.docker.com/engine/install/ubuntu/>
- <https://docs.docker.com/compose/install/linux/>

Verifikasi:

```bash
docker --version
docker compose version
docker info
git --version
openssl version
```

Jika `docker info` hanya bekerja dengan `sudo`, tambahkan user ke grup Docker lalu login
ulang:

```bash
sudo usermod -aG docker "$USER"
newgrp docker
docker info
```

Pastikan port `8080` belum dipakai:

```bash
sudo ss -ltnp | grep ':8080' || echo 'Port 8080 tersedia'
```

## 3. Ambil source project

```bash
cd ~
git clone https://github.com/syberke/hotel-online.git
cd hotel-online
git switch main
git pull origin main
```

Semua perintah berikut dijalankan dari folder `~/hotel-online`.

## 4. Buat environment aplikasi

Jangan menulis API key atau password asli di `Dockerfile`, `docker-compose.yaml`,
`.env.example`, atau Git. Simpan secret aplikasi di `/etc/oasis-hotel/oasis.env`.

```bash
sudo install -d -m 750 -o root -g "$(id -gn)" /etc/oasis-hotel
sudo nano /etc/oasis-hotel/oasis.env
```

Isi minimal berikut, lalu tambahkan Midtrans, reCAPTCHA, dan SMTP milikmu:

```env
APP_NAME="Oasis Hotel"
APP_ENV=production
APP_KEY=base64:ISI_APP_KEY_LARAVEL
APP_DEBUG=false
APP_URL=http://IP_SERVER:8080
DOCKER_APP_URL=http://IP_SERVER:8080

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_LEVEL=error

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false

CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@oasishotel.com"
MAIL_FROM_NAME="Oasis Hotel & Resort"
```

Ganti `IP_SERVER` dengan IP Ubuntu, misalnya `10.10.10.11`. Nilai koneksi database tidak
perlu ditulis di file ini karena Compose akan mengarahkannya ke MariaDB container.

Jika belum memiliki `APP_KEY`, buat tanpa PHP lokal:

```bash
printf 'base64:%s\n' "$(openssl rand -base64 32)"
```

Salin hasilnya ke `APP_KEY`, lalu amankan file:

```bash
sudo chmod 640 /etc/oasis-hotel/oasis.env
sudo chown root:"$(id -gn)" /etc/oasis-hotel/oasis.env
```

## 5. Buat credential MariaDB

Credential database dipisahkan dari environment aplikasi agar tidak ikut tersalin ke
image. Jalankan blok berikut satu kali:

```bash
DATABASE_PASSWORD="$(openssl rand -hex 32)"
DATABASE_ROOT_PASSWORD="$(openssl rand -hex 32)"
DATABASE_TMP="$(mktemp)"
chmod 600 "$DATABASE_TMP"

{
  printf 'MARIADB_DATABASE=oasis_hotel\n'
  printf 'MARIADB_USER=oasis_hotel\n'
  printf 'MARIADB_PASSWORD=%s\n' "$DATABASE_PASSWORD"
  printf 'MARIADB_ROOT_PASSWORD=%s\n' "$DATABASE_ROOT_PASSWORD"
  printf 'DB_DATABASE=oasis_hotel\n'
  printf 'DB_USERNAME=oasis_hotel\n'
  printf 'DB_PASSWORD=%s\n' "$DATABASE_PASSWORD"
} > "$DATABASE_TMP"

sudo install -m 640 -o root -g "$(id -gn)" \
  "$DATABASE_TMP" /etc/oasis-hotel/database.env

rm -f "$DATABASE_TMP"
unset DATABASE_PASSWORD DATABASE_ROOT_PASSWORD DATABASE_TMP
```

Jangan menghapus `/etc/oasis-hotel/database.env` selama volume database lama masih
digunakan. Password baru tidak otomatis mengubah user pada data MariaDB yang sudah ada.

## 6. Buat custom Dockerfile aplikasi

File `Dockerfile` di root project membangun aplikasi dalam tiga tahap:

1. `frontend`: menjalankan `npm ci` dan `npm run build` untuk menghasilkan aset Vite;
2. `vendor`: menjalankan Composer production;
3. runtime: PHP 8.3 + Apache dengan ekstensi Laravel yang diperlukan.

Isi file `Dockerfile`:

```dockerfile
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
COPY public ./public
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist \
    --optimize-autoloader --no-scripts --no-progress \
    --ignore-platform-req=ext-gd

FROM php:8.3-apache-bookworm
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev libicu-dev libjpeg62-turbo-dev libpng-dev \
        curl libpq-dev libwebp-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath gd intl opcache pdo_mysql pdo_pgsql zip \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/*

ARG APP_BUILD_REVISION=local
LABEL org.opencontainers.image.revision=$APP_BUILD_REVISION

WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
RUN rm -f public/hot && test -f public/build/manifest.json

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/entrypoint.sh /usr/local/bin/hotel-entrypoint

RUN chmod +x /usr/local/bin/hotel-entrypoint \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
ENTRYPOINT ["hotel-entrypoint"]
CMD ["apache2-foreground"]
```

`npm run dev` tidak dijalankan di server production. Aset CSS/JS sudah dibangun pada
tahap `frontend` dan disalin ke `public/build`.

## 7. Buat konfigurasi Apache dan entrypoint

File `docker/apache/000-default.conf` mengarahkan Apache ke folder `public` Laravel dan
menambahkan header identitas web node:

```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Options -Indexes +FollowSymLinks
        Require all granted
    </Directory>

    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    PassEnv APP_NODE_NAME APP_NODE_COLOR
    Header always set X-App-Node "%{APP_NODE_NAME}e"

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

File `docker/entrypoint.sh` menyiapkan permission dan cache Laravel setiap container
dimulai:

```sh
#!/bin/sh
set -eu

mkdir -p storage/framework/cache/data storage/framework/sessions \
  storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan package:discover --ansi
php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
```

Pastikan executable:

```bash
chmod +x docker/entrypoint.sh
```

## 8. Buat konfigurasi Nginx load balancer

Isi `docker/nginx/load-balancer.conf`:

```nginx
upstream hotel_web_cluster {
    zone hotel_web_cluster 64k;
    least_conn;
    server web1:80 max_fails=3 fail_timeout=10s;
    server web2:80 max_fails=3 fail_timeout=10s;
    server web3:80 max_fails=3 fail_timeout=10s;
    keepalive 32;
}

server {
    listen 80;
    server_name _;
    server_tokens off;

    add_header X-Load-Balancer "oasis-nginx" always;

    location = /lb-health {
        access_log off;
        default_type text/plain;
        return 200 "load balancer healthy\n";
    }

    location / {
        proxy_pass http://hotel_web_cluster;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
        proxy_set_header Host $http_host;
        proxy_set_header X-Forwarded-Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_connect_timeout 5s;
        proxy_read_timeout 60s;
        proxy_next_upstream error timeout http_502 http_503 http_504;
    }
}
```

`least_conn` mengirim request baru ke web node yang sedang memiliki koneksi paling
sedikit. Nama `web1`, `web2`, dan `web3` dapat ditemukan Nginx melalui network Compose.

## 9. Buat docker-compose.yaml

Isi `docker-compose.yaml`:

```yaml
x-web: &web
  build:
    context: .
    dockerfile: Dockerfile
    args:
      APP_BUILD_REVISION: ${APP_BUILD_REVISION:-local}
  image: oasis-hotel-web:latest
  restart: unless-stopped
  env_file:
    - ${OASIS_ENV_FILE:-.env}
    - /etc/oasis-hotel/database.env
  environment: &web-environment
    APP_ENV: production
    APP_DEBUG: "false"
    APP_URL: ${DOCKER_APP_URL:-http://localhost:8080}
    DB_CONNECTION: mysql
    DB_HOST: database
    DB_PORT: 3306
  networks:
    - hotel_internal
  depends_on:
    database:
      condition: service_healthy
  volumes:
    - hotel_public_storage:/var/www/html/storage/app/public
  healthcheck:
    test: ["CMD", "curl", "-fsS", "http://localhost/up"]
    interval: 10s
    timeout: 3s
    retries: 5
    start_period: 30s

services:
  web1:
    <<: *web
    environment:
      <<: *web-environment
      APP_NODE_NAME: Web 1
      APP_NODE_COLOR: "#0f766e"

  web2:
    <<: *web
    environment:
      <<: *web-environment
      APP_NODE_NAME: Web 2
      APP_NODE_COLOR: "#b45309"

  web3:
    <<: *web
    environment:
      <<: *web-environment
      APP_NODE_NAME: Web 3
      APP_NODE_COLOR: "#7e22ce"

  loadbalancer:
    image: nginx:1.27-alpine
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./docker/nginx/load-balancer.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      web1:
        condition: service_healthy
      web2:
        condition: service_healthy
      web3:
        condition: service_healthy
    networks:
      - hotel_internal
    healthcheck:
      test: ["CMD", "wget", "-qO-", "http://127.0.0.1/lb-health"]
      interval: 10s
      timeout: 3s
      retries: 5

  database:
    image: mariadb:11.4
    restart: unless-stopped
    env_file:
      - /etc/oasis-hotel/database.env
    volumes:
      - hotel_database:/var/lib/mysql
    networks:
      - hotel_internal
    healthcheck:
      test: ["CMD-SHELL", "healthcheck.sh --connect --innodb_initialized"]
      interval: 5s
      timeout: 3s
      retries: 10

networks:
  hotel_internal:
    driver: bridge

volumes:
  hotel_database:
    driver: local
  hotel_public_storage:
    driver: local
```

Bagian penting:

- `restart: unless-stopped`: container restart setelah crash/reboot, kecuali dihentikan
  manual;
- `hotel_database`: menyimpan data MariaDB di luar lifecycle container;
- `hotel_public_storage`: menyimpan file upload publik;
- `hotel_internal`: network komunikasi database, web node, dan Nginx;
- `APP_NODE_NAME`/`APP_NODE_COLOR`: perbedaan tampilan setiap web node;
- hanya `loadbalancer` yang memetakan port `8080:80`.

Validasi syntax Compose sebelum build:

```bash
export OASIS_ENV_FILE=/etc/oasis-hotel/oasis.env
docker compose --env-file "$OASIS_ENV_FILE" config --quiet
```

## 10. Build custom image

Gunakan commit Git sebagai revision agar perubahan source mematahkan cache build:

```bash
export OASIS_ENV_FILE=/etc/oasis-hotel/oasis.env
export APP_BUILD_REVISION="$(git rev-parse HEAD)"

docker compose --env-file "$OASIS_ENV_FILE" build --pull
```

Pastikan aset Vite benar-benar masuk ke image:

```bash
docker run --rm --entrypoint sh oasis-hotel-web:latest -c \
  'test ! -f public/hot && test -f public/build/manifest.json'
```

## 11. Jalankan database lebih dahulu

```bash
docker compose --env-file "$OASIS_ENV_FILE" up -d database
docker compose --env-file "$OASIS_ENV_FILE" ps database
```

Tunggu sampai status database `healthy`:

```bash
until [ "$(docker inspect --format='{{.State.Health.Status}}' hotel-online-database-1 2>/dev/null)" = "healthy" ]; do
  echo 'Menunggu MariaDB...'
  sleep 3
done
```

Jika nama project Compose berbeda, cukup gunakan:

```bash
docker compose --env-file "$OASIS_ENV_FILE" up -d --wait database
```

## 12. Jalankan tiga web node

```bash
docker compose --env-file "$OASIS_ENV_FILE" up -d --wait web1 web2 web3
docker compose --env-file "$OASIS_ENV_FILE" ps web1 web2 web3
```

Periksa aplikasi dari dalam masing-masing container:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 curl -fsS http://localhost/up
docker compose --env-file "$OASIS_ENV_FILE" exec -T web2 curl -fsS http://localhost/up
docker compose --env-file "$OASIS_ENV_FILE" exec -T web3 curl -fsS http://localhost/up
```

## 13. Jalankan migrasi dan seed

Migrasi selalu aman dijalankan pada deployment/update:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 \
  php artisan migrate --force

docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 \
  php artisan migrate:status
```

Jalankan seeder pada database baru:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 \
  php artisan db:seed --force
```

Seeder tidak perlu dijalankan berulang pada database yang sudah berisi data operasional.

Optimalkan cache Laravel:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan optimize:clear
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan optimize
```

## 14. Jalankan load balancer

```bash
docker compose --env-file "$OASIS_ENV_FILE" up -d --wait loadbalancer
docker compose --env-file "$OASIS_ENV_FILE" ps
```

Uji health endpoint dan aplikasi:

```bash
curl -fsSI http://localhost:8080/lb-health
curl -fsSI http://localhost:8080/
```

Hasil sehat harus menunjukkan status `200` dan header:

```text
X-Load-Balancer: oasis-nginx
X-App-Node: Web 1
```

## 15. Buktikan tiga container menerima traffic

Jalankan request beberapa kali:

```bash
for i in 1 2 3 4 5 6 7 8 9; do
  curl -sI http://localhost:8080/ | grep -i X-App-Node
done
```

Output akan bergantian sesuai beban, misalnya:

```text
X-App-Node: Web 1
X-App-Node: Web 2
X-App-Node: Web 3
```

Badge node pada website juga menggunakan `APP_NODE_NAME` dan `APP_NODE_COLOR`, sehingga
Web 1, Web 2, dan Web 3 memiliki identitas warna berbeda.

## 16. Perintah manual lengkap untuk deployment berikutnya

```bash
cd ~/hotel-online
git pull origin main

export OASIS_ENV_FILE=/etc/oasis-hotel/oasis.env
export APP_BUILD_REVISION="$(git rev-parse HEAD)"

docker compose --env-file "$OASIS_ENV_FILE" config --quiet
docker compose --env-file "$OASIS_ENV_FILE" down --remove-orphans
docker compose --env-file "$OASIS_ENV_FILE" build --pull
docker compose --env-file "$OASIS_ENV_FILE" up -d --wait

docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan migrate --force
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan optimize:clear
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan optimize

docker compose --env-file "$OASIS_ENV_FILE" ps
curl -fsSI http://localhost:8080/lb-health
curl -fsSI http://localhost:8080/
```

## 17. Cara otomatis dengan deploy.sh

Setelah memahami langkah manual, deployment yang sama dapat dijalankan otomatis:

```bash
cd ~/hotel-online
git pull origin main
chmod +x deploy.sh
OASIS_ENV_FILE=/etc/oasis-hotel/oasis.env ./deploy.sh
```

Pada setup pertama tanpa environment:

```bash
./deploy.sh --setup-env
```

Script melakukan build, start seluruh service, menunggu health check, migrate, seed jika
data awal belum ada, optimasi Laravel, dan verifikasi endpoint.

## 18. Troubleshooting

### Container web unhealthy

```bash
docker compose --env-file "$OASIS_ENV_FILE" ps -a
docker compose --env-file "$OASIS_ENV_FILE" logs --tail=200 web1
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan about
```

### Load balancer unhealthy

```bash
docker compose --env-file "$OASIS_ENV_FILE" logs --tail=200 loadbalancer
docker compose --env-file "$OASIS_ENV_FILE" exec -T loadbalancer nginx -t
curl -fsSI http://localhost:8080/lb-health
```

### CSS/JS tidak muncul

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 sh -c '
  test ! -f public/hot
  test -f public/build/manifest.json
  find public/build/assets -maxdepth 1 -type f
'
```

Pastikan `APP_URL` dan `DOCKER_APP_URL` memakai IP server dan port `8080`, lalu rebuild:

```bash
export APP_BUILD_REVISION="$(git rev-parse HEAD)-$(date +%s)"
docker compose --env-file "$OASIS_ENV_FILE" up -d --build --wait
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan optimize:clear
```

### Error database atau seed

```bash
docker compose --env-file "$OASIS_ENV_FILE" logs --tail=200 database
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan migrate:status
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 php artisan db:show
```

### Lihat log Laravel

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1 \
  sh -c 'tail -n 200 storage/logs/laravel.log'
```

## 19. Backup dan pemulihan data

Backup MariaDB:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T database sh -c \
  'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  > "oasis-hotel-$(date +%F-%H%M).sql"
```

Menghentikan stack tanpa menghapus data:

```bash
docker compose --env-file "$OASIS_ENV_FILE" down
```

Jangan menjalankan `docker compose down -v` pada server berisi data penting karena opsi
`-v` menghapus volume `hotel_database` dan `hotel_public_storage`.
