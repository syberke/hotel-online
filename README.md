# Oasis Hotel Online

Sistem operasional hotel berbasis Laravel untuk reservasi, front office, kamar,
tamu, restoran, fasilitas, pembayaran, dan laporan manajemen.

## Arsitektur deployment

Stack menjalankan tiga node web identik dari custom image Apache/PHP. Nginx
mendistribusikan request dengan strategi `least_conn`, sedangkan PostgreSQL
menyimpan data pada named volume yang persisten.

```text
Browser :8080
     |
Nginx load balancer
     |
     +-- web1 (Apache + Laravel, hijau)
     +-- web2 (Apache + Laravel, jingga)
     +-- web3 (Apache + Laravel, ungu)
     |
PostgreSQL + hotel_database volume
```

Semua service memakai network bridge internal `hotel_internal` dan restart
policy `unless-stopped`. Database tidak diekspos ke host.

## Menjalankan aplikasi dengan Docker

Prasyarat: Docker Engine, Docker Compose v2, OpenSSL, dan port 8080 yang kosong.

Jika `.env` sudah berisi koneksi Supabase, email, reCAPTCHA, dan Midtrans, file
tersebut dapat langsung digunakan tanpa disalin atau diubah. Docker membaca
credential saat runtime dan `.dockerignore` memastikan `.env` tidak masuk image.

Deployment default menggunakan koneksi `DB_*` yang sudah ada di `.env`.
Kemudian jalankan:

```bash
chmod +x deploy.sh
./deploy.sh
```

Aplikasi tersedia di <http://localhost:8080>.

### Database PostgreSQL container opsional

Jika tidak ingin memakai Supabase dan ingin menjalankan PostgreSQL lokal, tambahkan
konfigurasi berikut ke `.env`:

```env
DOCKER_DATABASE_MODE=local
POSTGRES_DB=oasis_hotel
POSTGRES_USER=oasis_hotel
POSTGRES_PASSWORD=password-yang-kuat
```

Tanpa `DOCKER_DATABASE_MODE=local`, service database tidak dijalankan dan aplikasi
tetap memakai Supabase atau database eksternal dari konfigurasi `DB_*`.

## Membuktikan load balancing

Halaman publik menampilkan badge node dengan warna berbeda. Header respons juga
memuat identitas node. Jalankan request berulang:

```bash
for i in 1 2 3 4 5 6; do
  curl -sI http://localhost:8080 | grep -i X-App-Node
done
```

Hasil akan bergantian antara `Web 1`, `Web 2`, dan `Web 3` mengikuti kondisi
koneksi. Periksa kondisi stack dengan:

```bash
docker compose ps
docker compose logs -f loadbalancer web1 web2 web3
```

## Laporan

Laporan menggunakan satu halaman terpadu agar KPI hotel mudah dibandingkan.
Isi dibagi menjadi section Overview, Rooms, Gastronomy, dan Facilities. Export
Excel tetap menggunakan beberapa sheet agar data setiap divisi mudah diolah,
sedangkan PDF memakai format ringkasan eksekutif.

## Pengembangan lokal

```bash
composer run setup
composer run dev
```

Jalankan pemeriksaan sebelum membuat pull request:

```bash
composer test
./vendor/bin/pint --test
npm run build
```

## Catatan keamanan

- Jangan commit `.env`, credential, private key, atau backup database.
- Gunakan password database yang kuat dan `APP_DEBUG=false` di production.
- Endpoint admin, manager, receptionist, dan guest dibatasi berdasarkan role.
- Status pembayaran hanya boleh dianggap final setelah callback bertanda tangan
  dari Midtrans diterima.
- Pasang TLS pada reverse proxy atau platform ingress saat digunakan di internet.
