# Hotel Online

Aplikasi hotel berbasis Laravel yang dilengkapi autentikasi, reservasi, manajemen tamu, pembayaran, dan email verifikasi. Project ini dapat dijalankan secara lokal maupun melalui Docker untuk kebutuhan deployment.

## Fitur utama
- Autentikasi pengguna dan verifikasi email
- Manajemen kamar, tipe kamar, dan pemesanan
- Manajemen tamu dan fasilitas hotel
- Integrasi pembayaran Midtrans
- reCAPTCHA pada form registrasi
- Deployment dengan Docker, Nginx, PHP-FPM, dan PostgreSQL

## Persyaratan sebelum deploy
Pastikan server/host memiliki:
- Docker dan Docker Compose terinstal
- Port 8080 tersedia
- Domain atau IP publik yang siap dipakai
- Database PostgreSQL yang dapat diakses
- Kredensial SMTP yang valid untuk fitur email

## File yang perlu diubah saat deploy
Sebelum menjalankan deployment, ubah nilai berikut di file .env:
- APP_ENV=production
- APP_URL=https://domain-anda.com
- APP_DEBUG=false
- DB_CONNECTION=pgsql
- DB_HOST=nama-host-db
- DB_PORT=5432
- DB_DATABASE=nama_database
- DB_USERNAME=nama_user
- DB_PASSWORD=password_db
- MAIL_MAILER=smtp
- MAIL_HOST=smtp.provider.com
- MAIL_PORT=587
- MAIL_USERNAME=your@email.com
- MAIL_PASSWORD=app-password
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=noreply@domain-anda.com
- MAIL_FROM_NAME="Nama Hotel"
- RECAPTCHA_SITE_KEY=...
- RECAPTCHA_SECRET_KEY=...
- MIDTRANS_SERVER_KEY=...
- MIDTRANS_CLIENT_KEY=...

## Menjalankan deployment
```bash
chmod +x deploy.sh
./deploy.sh
```

Script deployment akan:
1. Membuat file .env dari .env.example jika belum ada
2. Membangun container Docker
3. Menginstal dependensi Laravel
4. Menjalankan migrasi database
5. Menyediakan storage link dan cache Laravel

Akses aplikasi setelah selesai:
```text
http://localhost:8080
```

## Struktur Docker
- app: container PHP-FPM + Laravel
- loadbalancer: container Nginx sebagai reverse proxy
- db: container PostgreSQL

## Catatan penting
- Jangan menjalankan migrasi fresh di production secara sembarangan karena akan menghapus data.
- Jika ingin mengisi data awal, gunakan APP_SEED_DATABASE=true saat deploy.
- Untuk production, gunakan HTTPS dan sertifikat TLS yang valid.
- Jika email tidak terkirim, cek kembali SMTP, port, dan password aplikasi email.

## Troubleshooting singkat
- Aplikasi blank / 500: cek log container dan pastikan .env benar.
- Error database: cek koneksi DB dan kredensial PostgreSQL.
- Error email: cek SMTP dan gunakan app password jika memakai Gmail/Outlook.
- Error asset: pastikan folder public/build tersedia dan build berhasil.
