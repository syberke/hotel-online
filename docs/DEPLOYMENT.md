# Panduan Deployment Docker

Panduan ini menyediakan dua cara deployment Oasis Hotel pada Ubuntu:

1. **Otomatis**, menggunakan `./deploy.sh`.
2. **Manual**, menjalankan setiap perintah Docker, migrasi, seed, optimasi, dan verifikasi satu per satu.

> Jangan commit `.env`, `/etc/oasis-hotel/oasis.env`, atau
> `/etc/oasis-hotel/database.env`. Semua file tersebut mengandung rahasia.

## Prasyarat

Pastikan tersedia:

- Docker Engine;
- Docker Compose v2;
- Git;
- OpenSSL;
- port `8080` yang belum digunakan.

Periksa instalasi:

```bash
docker --version
docker compose version
docker info
```

## Cara 1: deployment otomatis

```bash
cd ~/hotel-online
git pull origin main
chmod +x deploy.sh
./deploy.sh
```

Script otomatis melakukan:

- membuat atau menggunakan environment aplikasi;
- membuat credential MariaDB yang persisten;
- membangun custom image Apache, PHP, Composer, dan Vite;
- menjalankan MariaDB, tiga web node, serta Nginx load balancer;
- menunggu seluruh health check;
- menjalankan `php artisan migrate --force`;
- menjalankan `php artisan db:seed --force` hanya jika data awal belum lengkap;
- menjalankan `optimize:clear` dan `optimize`;
- memeriksa endpoint aplikasi dan load balancer.

Seeder tidak dijalankan ulang jika data dasar sudah lengkap agar deployment berikutnya
tidak mereset data operasional.

## Cara 2: deployment manual

### 1. Ambil source terbaru

```bash
cd ~/hotel-online
git pull origin main
```

### 2. Siapkan environment aplikasi

Gunakan salah satu lokasi berikut.

Environment di folder project:

```bash
nano .env
export OASIS_ENV_FILE="$(realpath .env)"
```

Atau environment server yang lebih aman:

```bash
sudo install -d -m 750 -o root -g "$(id -gn)" /etc/oasis-hotel
sudo nano /etc/oasis-hotel/oasis.env
sudo chmod 640 /etc/oasis-hotel/oasis.env
export OASIS_ENV_FILE=/etc/oasis-hotel/oasis.env
```

Minimal konfigurasi production:

```env
APP_NAME="Oasis Hotel"
APP_ENV=production
APP_KEY=base64:HASIL_APP_KEY
APP_DEBUG=false
APP_URL=http://IP_SERVER:8080
DOCKER_APP_URL=http://IP_SERVER:8080
```

Konfigurasi Midtrans, reCAPTCHA, SMTP, dan layanan lain tetap ditulis pada file
environment aplikasi. Nilai koneksi database aplikasi akan diarahkan ke container
MariaDB oleh Docker Compose.

### 3. Siapkan credential MariaDB

Lewati langkah ini jika `/etc/oasis-hotel/database.env` sudah tersedia.

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

sudo install -d -m 750 -o root -g "$(id -gn)" /etc/oasis-hotel
sudo install -m 640 -o root -g "$(id -gn)"   "$DATABASE_TMP" /etc/oasis-hotel/database.env

rm -f "$DATABASE_TMP"
unset DATABASE_PASSWORD DATABASE_ROOT_PASSWORD DATABASE_TMP
```

Jangan menghapus `database.env` selama volume database masih digunakan. MariaDB
membutuhkan credential yang sama untuk mengakses data lama.

### 4. Build dan jalankan container

```bash
export APP_BUILD_REVISION="$(git rev-parse HEAD)"

docker compose --env-file "$OASIS_ENV_FILE" down --remove-orphans
docker compose --env-file "$OASIS_ENV_FILE" up -d --build --wait
```

Stack yang dijalankan:

- `database`: MariaDB dengan volume persisten;
- `web1`, `web2`, `web3`: Apache dan Laravel;
- `loadbalancer`: Nginx pada port `8080`.

### 5. Jalankan migrasi

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan migrate --force
```

Periksa status:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan migrate:status
```

### 6. Jalankan seed pada deployment pertama

Jalankan sekali ketika database baru atau data master belum tersedia:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan db:seed --force
```

Jangan menjalankan seeder berulang pada server yang sudah memiliki data operasional,
kecuali memang ingin memperbarui data demo/master sesuai perilaku seeder.

### 7. Optimalkan Laravel

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan optimize:clear

docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan optimize
```

### 8. Verifikasi deployment

```bash
docker compose --env-file "$OASIS_ENV_FILE" ps
curl -fsSI http://localhost:8080/lb-health
curl -fsSI http://localhost:8080
```

Buktikan pembagian traffic:

```bash
for i in 1 2 3 4 5 6; do
  curl -sI http://localhost:8080 | grep -i X-App-Node
done
```

## Update aplikasi secara manual

```bash
cd ~/hotel-online
git pull origin main
export OASIS_ENV_FILE="$(realpath .env)"
export APP_BUILD_REVISION="$(git rev-parse HEAD)"

docker compose --env-file "$OASIS_ENV_FILE" up -d --build --wait
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan migrate --force
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan optimize:clear
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   php artisan optimize
```

Seeder tidak perlu dijalankan pada update normal.

## Log dan troubleshooting

```bash
docker compose --env-file "$OASIS_ENV_FILE" ps -a
docker compose --env-file "$OASIS_ENV_FILE" logs -f --tail=150
docker compose --env-file "$OASIS_ENV_FILE" logs --tail=150 web1
docker compose --env-file "$OASIS_ENV_FILE" logs --tail=150 loadbalancer
```

Periksa log Laravel:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   sh -c 'tail -n 150 storage/logs/laravel.log'
```

Periksa aset Vite:

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T web1   test -f public/build/manifest.json
```

## Backup database

```bash
docker compose --env-file "$OASIS_ENV_FILE" exec -T database sh -c   'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'   > "oasis-hotel-$(date +%F-%H%M).sql"
```

## Menghentikan stack

Hentikan container tanpa menghapus data:

```bash
docker compose --env-file "$OASIS_ENV_FILE" down
```

Jangan memakai `docker compose down -v` pada server yang memiliki data penting.
Opsi `-v` menghapus volume MariaDB beserta seluruh datanya.
