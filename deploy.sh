#!/bin/bash

# Menghentikan script jika terjadi error di tengah jalan
set -e

echo "======================================================================="
echo "   OASIS HOTEL & RESORT - DEPLOYMENT AUTOMATION CLUSTER (VM 1)       "
echo "======================================================================="

# 1. Pembuatan Berkas Environment (.env) Manual
echo "⏳ Langkah 1: Memeriksa berkas .env..."
if [ ! -f .env ]; then
    echo "📄 Membuat berkas .env baru dari input manual..."

    # ----------------------------------------------------------------------
    # SILAKAN MODIFIKASI / TARUH ISI FILE .ENV KAMU DI ANTARA TANDA DI BAWAH INI
    # ----------------------------------------------------------------------
    cat << 'EOF' > .env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:3KKgCmi5iex5pNUEMV0IS5SJtwjAZ3wCx0m1I4Ke348=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.jacpuntkzxlybffwitvw
DB_PASSWORD=DPUqjtwQSiqbYryb

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

MIDTRANS_SERVER_KEY=SB-Mid-server-ylzHCJSSnSq-hEHVPb6Q2sN8
MIDTRANS_CLIENT_KEY=SB-Mid-client-MLayLQX4TB46A_Tw
MIDTRANS_IS_PRODUCTION=false
RECAPTCHA_SITE_KEY=6LdP_T0tAAAAAICKfSw-BrN3XJnrtpuSdfKKBSh6
RECAPTCHA_SECRET_KEY=6LdP_T0tAAAAAI0IZvXXX0XSufWouLW3vM7q8KAC


MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=berkejaisyurrohman95@gmail.com
MAIL_PASSWORD=vojjybtpecveaumk
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@oasishotel.com"
MAIL_FROM_NAME="Oasis Hotel & Resort"

EOF
    # ----------------------------------------------------------------------
    # AKHIR DARI TEMPAT MENARUH ISI .ENV
    # ----------------------------------------------------------------------

    echo "✅ Berkas .env berhasil dibuat berdasarkan konfigurasi manualmu."
else
    echo "ℹ️ Berkas .env sudah ada di direktori. Menggunakan file yang sudah ada."
fi

# =======================================================================
# SINKRONISASI WAKTU SISTEM (Mencegah Error x509 SSL Docker Hub)
# =======================================================================
echo "⏰ Menyinkronkan waktu sistem..."

sudo timedatectl set-timezone Asia/Jakarta || true
sudo timedatectl set-ntp true || true
sudo systemctl restart systemd-timesyncd || true

echo "⌛ Menunggu sinkronisasi waktu..."

for i in {1..30}; do
    if [ "$(timedatectl show -p NTPSynchronized --value 2>/dev/null)" = "yes" ]; then
        echo "✅ Waktu berhasil tersinkron."
        break
    fi
    sleep 1
done

echo "🕒 Waktu sistem saat ini:"
date
timedatectl

echo "🔄 Me-restart Docker daemon..."
sudo systemctl restart docker || true
sleep 5

echo "🧪 Menguji koneksi Docker Hub..."
docker pull hello-world >/dev/null 2>&1 && \
echo "✅ Docker Hub dapat diakses." || \
echo "⚠️ Docker Hub belum dapat diakses. Build mungkin gagal jika koneksi atau waktu sistem masih bermasalah."

# 2. Pembersihan Container Lama (Mencegah Port Terkunci)
echo "🧹 Langkah 2: Membersihkan sisa orkestrasi kontainer lama..."
docker-compose down --volumes --remove-orphans || true

# 3. Pembangunan & Pemicuan Kluster (Scaling app=3)
echo "🧱 Langkah 3: Merakit Custom Dockerfile & Menyalakan Kluster (Scale app=3)..."
# Di sinilah tempat kontainer dinyalakan dengan fitur scaling 3 replika
docker-compose up -d --build --scale app=3

# 4. Instalasi Dependency PHP via Composer di Dalam Kontainer
echo "📥 Langkah 4: Memasang library PHP Laravel di dalam kontainer utama..."
docker-compose exec -T app composer install --no-dev --optimize-autoloader

# 5. Pembuatan Application Encryption Key (Hanya digenerate jika belum ada di .env)
echo "🔑 Langkah 5: Memastikan Application Key Laravel tersedia..."
docker-compose exec -T app php artisan key:generate

# 6. Pengaturan Hak Akses Folder (Permission Fix)
echo "🔒 Langkah 6: Mengamankan hak akses direktori storage dan cache..."
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

# 7. Eksekusi Migrasi Database & Seeder Data Awal Hotel
echo "🗄️ Langkah 7: Menjalankan migrasi database PostgreSQL & Seeder..."
docker-compose exec -T app php artisan migrate:fresh --seed

# 8. Otomatisasi Modifikasi View untuk Bukti Indikator Load Balancer (Trik Ujikom)
echo "🏷️ Langkah 8: Menyuntikkan kode indikator Hostname Container untuk bukti Load Balancer..."
LAYOUT_FILE="resources/views/layouts/guest.blade.php"
INDICATOR_CODE='<div class="fixed bottom-4 right-4 bg-neutral-900 text-white text-[10px] font-mono p-2 z-50 border border-neutral-700">PROCESSED BY CONTAINER ID: <span class="text-yellow-400 font-bold">{{ gethostname() }}</span></div>'

if [ -f "$LAYOUT_FILE" ]; then
    if ! grep -q "LB INDICATOR" "$LAYOUT_FILE"; then
        if grep -q "</x-guest-layout>" "$LAYOUT_FILE"; then
            sed -i "s|</x-guest-layout>|${INDICATOR_CODE}\n</x-guest-layout>|g" "$LAYOUT_FILE"
        else
            sed -i "s|</body>|${INDICATOR_CODE}\n</body>|g" "$LAYOUT_FILE"
        fi
        echo "✅ Kode indikator Hostname unik berhasil disuntikkan ke $LAYOUT_FILE."
    else
        echo "ℹ️ Kode indikator sudah ada di dalam berkas view."
    fi
else
    echo "⚠️ Berkas $LAYOUT_FILE tidak ditemukan. Lewati penyuntikan visual."
fi

## 9. Pembersihan & Optimasi Cache Laravel Produksi
echo "⚡ Langkah 9: Mengoptimalkan sistem cache internal aplikasi..."
docker-compose exec -T app php artisan config:cache || true
# Hapus atau komentari bagian view:cache di bawah ini agar tidak memicu crash component footer
# docker-compose exec -T app php artisan view:cache || true
docker-compose exec -T app php artisan cache:clear || true

echo "======================================================================="
echo "🎉 DEPLOYMENT SELESAI! Proyek Ujikom Berhasil Dijalankan di VM 1"
echo "🌐 Akses Web Hotel Oasis via Browser pada Port 8080: http://<IP_VM_1>:8080"
echo "======================================================================="
echo "======================================================================="
echo "🎉 DEPLOYMENT SELESAI! Proyek Ujikom Berhasil Dijalankan di VM 1"
echo "🌐 Akses Web Hotel Oasis via Browser pada Port 8080: http://<IP_VM_1>:8080"
echo "======================================================================="