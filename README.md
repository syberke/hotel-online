# Oasis Hotel Online

Sistem operasional hotel berbasis Laravel untuk reservasi, front office, kamar, guest, Room Service, restoran, venue, fasilitas, pembayaran, folio, dan laporan.

## Dokumentasi

- [Penjelasan aplikasi dan fitur](docs/HOTEL_ONLINE.md)
- [Ringkasan tugas dan checklist UKK](docs/UKK_MANUAL.md)
- [Tutorial Docker UKK lengkap dari nol](docs/DOCKER_UKK_LENGKAP.md)

## Kebijakan konfigurasi Docker

Repository utama sengaja **tidak menyediakan konfigurasi Docker siap pakai**.

File berikut harus dibuat manual di VM saat latihan atau ujian dengan mengikuti tutorial Docker:

```text
Dockerfile
entrypoint.sh
docker-compose.yml
nginx.conf
.env.docker
.dockerignore
```

Tujuannya agar peserta dapat menjelaskan dan mendemonstrasikan proses pembuatan custom image, database, network, volume, scaling tiga container web, serta load balancing sesuai tugas UKK.

## Pengembangan lokal tanpa Docker

Prasyarat:

- PHP 8.3 atau lebih baru;
- Composer;
- Node.js dan npm;
- PostgreSQL atau MariaDB.

Setup:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Mode development lengkap:

```bash
composer run dev
```

## Pemeriksaan

```bash
php artisan test
npm run build
```

Jangan commit `.env`, password database, key Midtrans, key reCAPTCHA, credential email, private key SSH, atau backup database.
