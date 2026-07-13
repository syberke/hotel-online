# Oasis Hotel Online

Sistem operasional hotel berbasis Laravel untuk reservasi, front office, kamar,
tamu, restoran, fasilitas, pembayaran, dan laporan manajemen.

## Arsitektur deployment

Stack menjalankan tiga node web identik dari custom image Apache/PHP. Nginx
mendistribusikan request dengan strategi `least_conn`, sedangkan MariaDB
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
MariaDB + hotel_database volume
```

Semua service memakai network bridge internal `hotel_internal` dan restart
policy `unless-stopped`. Database tidak diekspos ke host.

## Menjalankan aplikasi dengan Docker

Prasyarat: Docker Engine, Docker Compose v2, OpenSSL, dan port 8080 yang kosong.

Jika `.env` lama masih berisi koneksi Supabase, email, reCAPTCHA, dan Midtrans,
file tersebut dapat langsung digunakan tanpa mengubah API key satu per satu.
Saat deployment, nilai koneksi `DB_*` di dalam container otomatis diarahkan ke
MariaDB. `.dockerignore` memastikan `.env` tidak masuk ke image.

Untuk deployment Ubuntu yang lebih aman dan tidak terganggu `git pull`, simpan
environment satu kali di `/etc/oasis-hotel/oasis.env`. Script deployment akan
mendeteksinya otomatis jika `.env` tidak ada di folder project:

```bash
sudo install -d -m 750 /etc/oasis-hotel
sudo nano /etc/oasis-hotel/oasis.env
sudo chown root:$(id -gn) /etc/oasis-hotel /etc/oasis-hotel/oasis.env
sudo chmod 750 /etc/oasis-hotel
sudo chmod 640 /etc/oasis-hotel/oasis.env
```

File tersebut tidak berada di repository dan tidak masuk ke Docker image. Untuk
lokasi custom, jalankan `OASIS_ENV_FILE=/lokasi/rahasia.env ./deploy.sh`.

Jika belum ada environment, cukup jalankan `./deploy.sh`. Script akan meminta
seluruh isi `.env` melalui input terminal yang disembunyikan, membuat APP_KEY
jika kosong, dan menyimpannya ke lokasi permanen tersebut. Untuk mengganti
environment di kemudian hari, jalankan:

```bash
./deploy.sh --setup-env
```

Kemudian jalankan:

```bash
chmod +x deploy.sh
./deploy.sh
```

Script otomatis menjalankan migrasi, seeding kondisional, optimasi cache, serta
verifikasi endpoint. Untuk menjalankan setiap tahap secara manual, termasuk
pembuatan credential MariaDB, build, migrate, seed, dan troubleshooting, baca
[Panduan Deployment Docker](docs/DEPLOYMENT.md).

Aplikasi tersedia di <http://localhost:8080>.

### Database MariaDB

MariaDB selalu dijalankan sebagai container internal. Pada deployment pertama,
`deploy.sh` membuat user dan password acak yang kuat lalu menyimpannya di
`/etc/oasis-hotel/database.env` dengan permission terbatas. Jangan menghapus file
tersebut selama volume `hotel_database` masih digunakan karena credential harus
tetap sama. Data database disimpan di named volume Docker dan tidak hilang saat
container dibuat ulang.

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
