# Dokumentasi Oasis Hotel Online

Dokumen ini menjelaskan tujuan, fitur, role pengguna, alur bisnis, data utama, pembayaran, dan cara menguji aplikasi **Oasis Hotel Online**.

Untuk panduan praktik deployment UKK dengan Ubuntu, Docker, Nginx, MariaDB, SSH, dan FTP, gunakan dokumen terpisah:

- [`UKK_MANUAL.md`](UKK_MANUAL.md)

---

## 1. Gambaran aplikasi

Oasis Hotel Online adalah aplikasi manajemen hotel berbasis web yang menghubungkan proses berikut dalam satu sistem:

- informasi hotel untuk pengunjung;
- pencarian dan pemesanan kamar;
- pembayaran reservasi;
- portal guest selama menginap;
- Room Service dan tagihan folio;
- reservasi fasilitas;
- katalog restoran dan venue tempat makan;
- operasional Front Desk;
- pengelolaan kamar dan inventori;
- laporan operasional dan finansial;
- pengelolaan pesan Contact;
- akses berbasis role.

Aplikasi menggunakan server-rendered Laravel Blade. Navigasi antarhalaman tetap memuat dokumen baru, tetapi posisi scroll sidebar staf disimpan agar tidak selalu kembali ke atas.

---

## 2. Teknologi utama

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.3+ dan Laravel 13 |
| Tampilan | Blade, Tailwind CSS, Alpine.js |
| Build frontend | Vite dan npm |
| Database development/cloud | PostgreSQL, termasuk Supabase |
| Database UKK Docker | MariaDB |
| Pembayaran online reservasi | Midtrans Snap |
| Autentikasi | Laravel Breeze, email verification, password reset |
| Proteksi form | CSRF dan reCAPTCHA jika key tersedia |
| PWA | Web App Manifest dan Service Worker |
| Laporan | PhpSpreadsheet dan export laporan |
| Pengujian | PHPUnit melalui `php artisan test` |

Aplikasi dibuat agar query dan enum penting dapat berjalan pada PostgreSQL dan MariaDB.

---

## 3. Role dan hak akses

Sistem memiliki empat role utama:

1. Guest;
2. Receptionist;
3. Manager;
4. Admin.

### 3.1 Ringkasan hak akses

| Modul | Guest | Receptionist | Manager | Admin |
|---|---:|---:|---:|---:|
| Halaman publik | Ya | Ya | Ya | Ya |
| Booking kamar pribadi | Ya | Tidak | Lihat | Kelola |
| Portal My Stay | Ya | Tidak | Tidak | Tidak |
| Room Service | Pesan | Lihat tagihan melalui folio | Lihat laporan | Kelola status order |
| Check-in dan check-out | Tidak | Ya | Lihat | Lihat |
| Room Assignment | Tidak | Ya | Lihat data kamar | Kelola inventori tertentu |
| Folio | Tidak langsung | Lihat dan proses pembayaran | Lihat dan cetak | Lihat dan cetak |
| Guest History | Data pribadi | Lihat dan edit identitas | Lihat melalui modul staf | Lihat melalui modul staf |
| Venue restoran | Lihat venue aktif | Tidak dikelola | CRUD venue | CRUD venue |
| Contact Inbox | Kirim pesan | Tidak | Read-only | Update status dan hapus |
| Reports | Tidak | Tidak | Ya | Ya |
| User management | Profil sendiri | Tidak | Read-only sesuai halaman | Kelola akun |

### 3.2 Aturan Manager

Manager pada umumnya bersifat read-only untuk data operasional sensitif. Pengecualian yang saat ini diberikan adalah CRUD venue restoran karena venue merupakan data pengelolaan layanan dan tampilan publik.

Manager dapat membuka endpoint detail tombol mata untuk membaca data, tetapi tidak memperoleh hak edit hanya karena endpoint detail dapat dibuka.

---

## 4. Fitur publik

### 4.1 Home

Home berfungsi sebagai landing page hotel dan menampilkan:

- pengenalan hotel;
- kamar unggulan;
- fasilitas;
- restoran;
- lokasi hotel jika koordinat sudah dikonfigurasi;
- FAQ;
- call-to-action menuju Rooms, Restaurant, Facilities, dan Contact.

Form Check Room tidak ditempatkan di Home. Pemeriksaan tanggal dan ketersediaan dilakukan di halaman Rooms agar alurnya lebih jelas.

### 4.2 Rooms

Halaman Rooms menyediakan:

- daftar tipe dan unit kamar yang dapat ditampilkan;
- harga kamar;
- kapasitas;
- fasilitas kamar;
- detail kamar;
- pemilihan tanggal menginap;
- proses booking untuk pengguna yang sudah login.

### 4.3 Restaurant

Halaman Restaurant membaca menu dan venue dari database.

Data menu dapat memuat:

- nama;
- kategori;
- deskripsi;
- harga;
- gambar;
- status tersedia.

Data venue dapat memuat:

- nama venue;
- lokasi;
- deskripsi;
- gambar;
- jam buka dan tutup;
- kapasitas;
- status aktif;
- status menerima reservasi;
- urutan tampil.

Bagian venue bukan array dummy. Data berasal dari tabel `restaurant_venues` dan dapat dikelola melalui tab Venue pada halaman Restaurant staf.

### 4.4 Facilities

Halaman Facilities menampilkan fasilitas hotel dari database, termasuk kategori, jam operasional, gambar, dan status perlu reservasi.

### 4.5 Contact

Form Contact berfungsi menyimpan pesan ke database.

Alur:

```text
Pengunjung mengirim Contact
→ validasi server
→ tersimpan pada contact_messages
→ muncul di Contact Inbox
→ Admin memperbarui status atau menghapus
→ staf dapat membalas melalui email atau menelepon
```

Status Contact:

- `new`;
- `in_progress`;
- `resolved`.

Tombol **Reply by email** membuka Gmail Compose dengan alamat, subject, dan template balasan. Tombol **Call** menyalin nomor dan membuka URI `tel:`. Keberhasilan panggilan pada laptop bergantung pada aplikasi dialer yang terpasang.

Alamat, email, nomor telepon, WhatsApp, dan koordinat hotel harus diambil dari environment. Sistem tidak seharusnya menampilkan koordinat lokasi contoh.

### 4.6 Privacy dan Terms

Halaman Privacy dan Terms tersedia sebagai halaman legal. Persetujuan Terms pada Register divalidasi di frontend dan backend.

---

## 5. Autentikasi dan keamanan akun

Fitur autentikasi meliputi:

- Register;
- Login;
- Keep me signed in;
- Forgot Password;
- Reset Password;
- Verify Email;
- Confirm Password;
- Logout;
- edit Profile;
- hapus akun sesuai alur aplikasi.

### 5.1 Email verification

Akun baru diarahkan ke verifikasi email. Pengguna dapat meminta pengiriman ulang link verifikasi.

### 5.2 reCAPTCHA

reCAPTCHA aktif jika site key dan secret key diisi. Jika key tidak tersedia, aplikasi tidak seharusnya menampilkan widget kosong.

### 5.3 Keep me signed in

Checkbox Keep me signed in menggunakan input checkbox normal dan seluruh area label dapat diklik.

### 5.4 Proteksi role

Middleware membatasi akses berdasarkan role. Manager hanya diperbolehkan membaca endpoint detail tertentu. Guest tidak dapat membuka folio staf hanya dengan menebak URL atau ID booking.

---

## 6. Portal Guest

### 6.1 Guest Dashboard

Dashboard Guest memberikan ringkasan:

- booking aktif;
- status menginap;
- kamar;
- tanggal check-in dan check-out;
- shortcut ke My Bookings, My Stay, Room Service, Restaurant Orders, Facilities, dan Profile.

### 6.2 My Bookings

My Bookings menampilkan:

- booking pending;
- booking confirmed;
- checked-in;
- checked-out;
- booking selesai;
- pembayaran booking;
- pembatalan jika masih memenuhi aturan;
- receipt untuk booking yang sudah confirmed, checked-in, atau checked-out.

Pembayaran reservasi kamar dapat menggunakan Midtrans ketika konfigurasi tersedia.

### 6.3 My Stay

My Stay tetap dapat dibuka untuk status:

- `confirmed`;
- `checked_in`;
- `checked_out`.

Saat checked-in, akses layanan aktif. Setelah checked-out, kunci dan action layanan dikunci, tetapi histori menginap serta receipt tetap dapat dilihat.

### 6.4 Profile

Guest dapat mengubah data akun dan profil yang diizinkan. Identitas operasional juga dapat diperbarui oleh Receptionist melalui Guest History.

### 6.5 Restaurant Orders

Guest dapat melihat pesanan restoran dan status transaksinya. Pembayaran restoran biasa dapat berbeda dari Room Service tergantung alur yang dipilih aplikasi.

### 6.6 Facilities Booking

Guest dapat memilih fasilitas, tanggal, waktu, jumlah tamu, preferensi, dan catatan. Data masuk ke `facility_bookings`.

---

## 7. Room Service dan folio

### 7.1 Apakah Room Service gratis?

Room Service tidak otomatis termasuk harga kamar. Harga kamar dapat mencakup akomodasi dan fasilitas dasar. Sarapan hanya dianggap termasuk jika paket kamar memang menyebutkannya.

Room Service adalah pesanan makanan atau minuman tambahan ke kamar, sehingga tetap menjadi tagihan.

### 7.2 Mengapa tidak ada popup pembayaran?

Guest tidak perlu membayar Room Service saat menekan tombol order.

Alur yang digunakan:

```text
Guest memilih menu
→ Place order & add to folio
→ restaurant_orders dibuat
→ restaurant_order_details dibuat
→ payments dibuat dengan status pending
→ biaya muncul pada folio booking
→ Front Desk menyelesaikan pembayaran sebelum checkout
```

Record pending Room Service memiliki:

- `booking_id`;
- `restaurant_order_id`;
- `amount`;
- `payment_method = cash` sebagai nilai awal folio;
- `payment_status = pending`;
- catatan Room Service dan nomor kamar.

Nilai `cash` pada record pending bukan berarti guest sudah membayar tunai. Itu hanya metode awal yang dapat diganti saat Front Desk melakukan settlement.

### 7.3 Perhitungan folio

Kalkulasi folio menggunakan satu service bersama agar halaman Folio, Payments, dan Checkout menghasilkan angka yang sama.

```text
Total Charges = Harga kamar + Room Service yang tidak dibatalkan
Total Payments = Semua pembayaran paid untuk booking tersebut
Balance Due = Total Charges - Total Payments
```

Room Service ditampilkan sebagai debit, misalnya:

```text
Room Service #0003 · 1× Wagyu Ribeye Steak
```

Pembayaran ditampilkan sebagai kredit.

### 7.4 Settlement Room Service

Saat Receptionist memproses pembayaran folio:

- pembayaran kamar dicatat sebagai payment paid;
- record Room Service pending yang tercakup diubah menjadi paid;
- metode pembayaran mengikuti pilihan Receptionist;
- restaurant order terkait diubah menjadi paid.

Satu item Room Service tidak dipotong secara parsial. Nominal pembayaran harus cukup untuk melunasi item Room Service yang dialokasikan.

### 7.5 Aturan checkout

Checkout hanya dapat diselesaikan jika `balance_due = 0`.

Jika masih ada saldo:

```text
Check-out ditahan
→ Receptionist diarahkan ke Folio Payment
→ saldo kamar dan Room Service ditampilkan
→ payment diproses
→ kembali ke Checkout
→ Confirm check-out aktif setelah lunas
```

Setelah checkout:

- booking menjadi `checked_out`;
- kamar menjadi `maintenance`;
- kamar harus ditandai `available` setelah siap digunakan kembali.

---

## 8. Receptionist / Front Desk

### 8.1 Dashboard

Reception Dashboard menampilkan:

- occupancy;
- check-in hari ini;
- check-out hari ini;
- guest in-house;
- revenue hari ini;
- expected arrivals;
- occupancy trend;
- room status;
- Tasks & Alerts.

### 8.2 Tasks & Alerts

Alert harus menyebutkan data yang membutuhkan tindakan, bukan hanya jumlah.

Contoh:

- nomor kamar maintenance;
- booking yang masih memiliki saldo;
- nama guest;
- nilai yang belum dibayar;
- booking yang membutuhkan konfirmasi kamar;
- tombol menuju Payments, Room Assignment, atau House Status.

### 8.3 Reservations

Receptionist dapat melihat dan mencari reservasi berdasarkan data yang tersedia, termasuk guest, booking ID, dan status.

### 8.4 Room Assignment

Room Assignment membaca booking pending atau confirmed, pembayaran, tipe kamar, periode menginap, dan konflik jadwal.

Queue membedakan:

- `Paid`;
- `Needs payment`.

Saat kamar dialokasikan, sistem memeriksa:

- kamar berstatus available;
- tipe kamar sesuai;
- tidak ada booking lain yang bertabrakan;
- booking masih berada pada status yang diizinkan.

### 8.5 Check-in

Check-in memeriksa booking, status pembayaran, identitas guest, dan kesiapan kamar. Setelah berhasil:

- booking menjadi checked-in;
- kamar menjadi occupied.

### 8.6 Payments

Halaman Payments membaca kalkulasi folio yang sama dengan Checkout dan Folio.

Receptionist dapat memilih:

- cash;
- transfer;
- credit card;
- e-wallet.

### 8.7 Checkout

Checkout menampilkan seluruh debit dan kredit, termasuk Room Service. Tombol checkout tidak tersedia jika saldo belum nol.

### 8.8 Folio

Folio memuat:

- data booking;
- guest;
- kamar;
- room charge;
- Room Service;
- pembayaran;
- running balance;
- total per departemen;
- reservasi fasilitas selama periode menginap;
- tombol print.

### 8.9 Guest History

Guest History menampilkan histori booking dan memungkinkan Receptionist mengubah:

- nama;
- nomor telepon;
- nomor identitas;
- alamat.

Email tidak diubah dari editor identitas karena menjadi penghubung akun, profil guest, dan histori booking.

### 8.10 Room Availability dan House Status

Status kamar final hanya:

- `available`;
- `occupied`;
- `maintenance`.

Nilai `dirty` tidak dipakai pada enum final PostgreSQL. Setelah checkout, kamar masuk maintenance sampai staf menandainya available.

---

## 9. Admin dan Manager

### 9.1 Dashboard

Dashboard staf menampilkan data reservasi, okupansi, revenue, arrival, performa kamar, aktivitas terbaru, dan status kamar.

### 9.2 Reservations dan Front Desk

Admin dapat menjalankan action pengelolaan yang tersedia. Manager melihat data dan detail tanpa memperoleh akses modifikasi sensitif.

### 9.3 Rooms & Inventory

Modul ini memuat:

- daftar kamar;
- tipe kamar;
- status kamar;
- detail melalui tombol mata;
- data booking aktif pada kamar;
- kapasitas;
- harga;
- CRUD inventori untuk role yang diizinkan.

Tombol mata memakai endpoint JSON read-only. Manager dapat membaca detail, tetapi tidak otomatis mendapat hak edit.

### 9.4 Restaurant: Orders dan Venues

Halaman Restaurant staf memiliki tab utama:

- Orders;
- Venues.

Tab Today’s Menu tidak digunakan pada layout staf saat ini.

#### Orders

Menampilkan:

- semua order;
- dine-in;
- Room Service;
- status ordered, preparing, paid, atau cancelled;
- guest;
- room jika terkait stay;
- revenue;
- top-selling items;
- detail order;
- update status untuk role yang diizinkan.

#### Venues

Form Add Venue hanya muncul ketika tab Venue dibuka.

Admin dan Manager dapat:

- menambah venue;
- melihat venue;
- mengubah venue;
- menghapus venue jika belum mempunyai histori yang harus dipertahankan;
- menonaktifkan venue;
- mengaktifkan atau menonaktifkan reservasi;
- mengatur urutan tampil.

Venue yang sudah memiliki histori reservasi sebaiknya dinonaktifkan, bukan dihapus.

### 9.5 Facilities

Admin dapat mengelola fasilitas dan status booking sesuai route yang tersedia. Manager menggunakan tampilan laporan atau read-only sesuai modul.

### 9.6 Finance

Finance mengambil data dari tabel payments dan membedakan:

- room revenue;
- food and beverage revenue;
- other revenue;
- metode pembayaran;
- payment status.

### 9.7 Reports

Reports membaca data nyata untuk:

- ringkasan operasional;
- room types;
- restaurant;
- facilities;
- top menu item;
- top facility;
- revenue dan performa.

Teks Blade seperti `@elif` tidak boleh muncul di output. Directive yang benar adalah `@elseif`.

### 9.8 Contact Inbox

Manager dapat membaca pesan. Admin dapat:

- membaca;
- mengubah status;
- membalas melalui email;
- membuka panggilan;
- menghapus pesan.

### 9.9 User management

Admin dapat mengelola akun sesuai action yang tersedia. Manager tidak memperoleh hak modifikasi akun hanya karena dapat membuka halaman detail.

---

## 10. Receipt dan detail modal

Receipt tersedia untuk booking confirmed, checked-in, dan checked-out yang dimiliki guest terkait.

Isi receipt dapat mencakup:

- identitas hotel;
- booking ID;
- guest;
- kamar;
- periode menginap;
- item biaya;
- total;
- payment status.

Modal detail dan receipt dibatasi berdasarkan viewport agar tidak keluar layar. Jika isi panjang, scroll terjadi di dalam modal.

Tombol mata dibuat konsisten untuk detail kamar, reservation, restaurant order, user, dan facility booking.

---

## 11. Progressive Web App

PWA menyediakan:

- manifest aplikasi;
- icon aplikasi;
- install prompt kustom;
- standalone display;
- cache halaman publik tertentu;
- offline fallback.

Halaman private dan dashboard tidak disimpan sebagai cache offline permanen. Request private menggunakan network agar data akun dan transaksi tidak menampilkan snapshot lama.

Jika tampilan PWA lama masih muncul setelah update:

```text
F12
→ Application
→ Service Workers
→ Unregister
→ Storage
→ Clear site data
→ Ctrl + Shift + R
```

Dialog install terakhir setelah tombol aplikasi ditekan dikontrol oleh Chrome atau Edge dan tidak dapat diberi CSS oleh Laravel.

---

## 12. Data utama

Tabel penting yang digunakan aplikasi antara lain:

| Tabel | Fungsi |
|---|---|
| `users` | akun, role, email, password, status akun |
| `guests` | profil guest, telepon, identitas, alamat, foto |
| `room_types` | nama tipe, harga, kapasitas, informasi tipe |
| `rooms` | unit kamar fisik dan status operasional |
| `bookings` | reservasi, periode, guest, kamar, harga, status |
| `payments` | pembayaran kamar, Room Service, dan transaksi terkait |
| `restaurant_menus` | menu, kategori, harga, gambar, ketersediaan |
| `restaurant_orders` | header pesanan restoran dan Room Service |
| `restaurant_order_details` | item dan quantity pesanan |
| `restaurant_venues` | venue tempat makan |
| `restaurant_reservations` | reservasi venue |
| `facilities` | master fasilitas |
| `facility_bookings` | reservasi fasilitas |
| `contact_messages` | pesan Contact dan status penanganan |

### 12.1 Status utama

#### Booking

- `pending`;
- `confirmed`;
- `checked_in`;
- `checked_out`;
- `cancelled` sesuai implementasi modul.

#### Room

- `available`;
- `occupied`;
- `maintenance`.

#### Payment

- `pending`;
- `paid`;
- `failed`.

#### Restaurant order

- `ordered`;
- `preparing`;
- `paid`;
- `cancelled`.

#### Facility booking

- `confirmed`;
- `completed`;
- `cancelled`.

---

## 13. Konfigurasi environment

Contoh kelompok environment yang perlu diperhatikan:

```env
APP_NAME="Oasis Hotel"
APP_ENV=local
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="Oasis Hotel"

HOTEL_PHONE=
HOTEL_EMAIL=
HOTEL_WHATSAPP=
HOTEL_ADDRESS=
HOTEL_LATITUDE=
HOTEL_LONGITUDE=
```

Jangan menyimpan key, password, atau credential production di Git.

Setelah mengubah `.env`:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 14. Instalasi lokal

```bash
git clone https://github.com/syberke/hotel-online.git
cd hotel-online

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Untuk development frontend:

```bash
npm run dev
```

Atau gunakan script Composer:

```bash
composer run dev
```

---

## 15. Seeder

Seeder yang relevan untuk restoran:

```bash
php artisan db:seed --class=RestaurantVenueSeeder
php artisan db:seed --class=RestaurantMenuSeeder
```

Seeder dipakai untuk menyediakan data awal yang dapat diuji. Setelah aplikasi digunakan secara nyata, data dapat dikelola dari database dan UI yang tersedia.

---

## 16. Build dan cache

Setelah menarik perubahan:

```bash
git pull origin main
composer install
npm install
php artisan migrate
php artisan optimize:clear
npm run build
```

Verifikasi manifest Vite:

```bash
test -f public/build/manifest.json && echo "Build tersedia"
```

Pada PowerShell:

```powershell
Test-Path .\public\build\manifest.json
```

---

## 17. Pengujian penting

Jalankan seluruh test:

```bash
php artisan test
```

Test khusus yang sering dipakai:

```bash
php artisan test --filter=RoomReceiptRouteTest
php artisan test --filter=OperationalContentRoutesTest
php artisan test --filter=StaffOperationsRoutesTest
php artisan test --filter=RolePathGuardTest
php artisan test --filter=RoomServicePaymentFlowTest
php artisan test --filter=EnumDatabaseCompatibilityTest
```

Kompilasi Blade:

```bash
php artisan view:cache
php artisan view:clear
```

Periksa route:

```bash
php artisan route:list
```

---

## 18. Skenario uji end-to-end

### 18.1 Booking kamar

```text
Register
→ verify email
→ login
→ pilih kamar dan tanggal
→ buat booking
→ pembayaran booking
→ status confirmed
```

### 18.2 Check-in

```text
Receptionist membuka booking
→ cek pembayaran dan identitas
→ konfirmasi kamar
→ check-in
→ booking checked_in
→ room occupied
```

### 18.3 Room Service sampai checkout

```text
Guest checked-in
→ pesan Room Service
→ order dan payment pending dibuat
→ buka Folio atau Checkout
→ Room Service terlihat sebagai debit
→ buka Payments
→ lunasi saldo
→ payment Room Service menjadi paid
→ restaurant order menjadi paid
→ Confirm check-out
→ booking checked_out
→ room maintenance
```

### 18.4 Venue restoran

```text
Admin atau Manager
→ Restaurant
→ buka tab Venues
→ Add Venue
→ simpan
→ venue muncul di halaman publik jika aktif
```

### 18.5 Contact

```text
Pengunjung mengirim Contact
→ Admin membuka Contact Inbox
→ Reply by email atau Call
→ status in_progress
→ status resolved
```

---

## 19. Fitur yang sengaja tidak digunakan

### 19.1 Walk-in

Modul Walk-in telah dihapus karena tidak digunakan pada layout dan alur demonstrasi saat ini. Route atau tombol tidak boleh mengarah ke Walk-in.

### 19.2 Today’s Menu pada halaman staf

Tab Today’s Menu telah dihapus dari halaman Restaurant staf dan diganti menjadi tab Venue. Menu tetap berasal dari database untuk katalog publik dan Room Service.

### 19.3 Pembayaran langsung Room Service

Room Service tidak membuka Midtrans atau popup pembayaran. Tagihan masuk ke folio dan diselesaikan oleh Front Desk sebelum checkout.

### 19.4 Status kamar dirty

Status `dirty` tidak dipakai dalam enum final. Kamar yang belum siap setelah checkout menggunakan status `maintenance`.

---

## 20. Troubleshooting singkat

### Asset atau style lama masih tampil

```bash
php artisan optimize:clear
npm run build
```

Kemudian hapus Service Worker dan site data dari browser.

### Route tidak ditemukan

```bash
php artisan route:clear
php artisan route:list
```

### Blade error

```bash
php artisan view:clear
php artisan view:cache
```

### Perubahan `.env` belum terbaca

```bash
php artisan config:clear
```

### Room Service tidak muncul saat checkout

Periksa bahwa record payment memiliki `booking_id` dan `restaurant_order_id`:

```sql
SELECT id, booking_id, restaurant_order_id, amount, payment_status, note
FROM payments
WHERE booking_id = ID_BOOKING
ORDER BY created_at;
```

### PostgreSQL menolak status kamar

Periksa enum:

```sql
SELECT enumlabel
FROM pg_enum
JOIN pg_type ON pg_type.oid = pg_enum.enumtypid
WHERE pg_type.typname = 'room_status_enum'
ORDER BY enumsortorder;
```

Hasil final:

```text
available
occupied
maintenance
```

---

## 21. Hubungan dengan UKK

Aplikasi ini menjadi workload yang dijalankan pada praktik UKK komputasi awan.

Dokumen ini menjelaskan **fungsi aplikasi**. Dokumen [`UKK_MANUAL.md`](UKK_MANUAL.md) menjelaskan **cara menyiapkan infrastrukturnya**, termasuk:

- dua VM Ubuntu;
- NAT dan Bridged Adapter;
- Docker;
- custom image;
- tiga web container;
- MariaDB;
- named volume;
- Docker network;
- Nginx load balancer;
- SSH tanpa password;
- FTP;
- pengujian jaringan dan layanan.

Gunakan kedua dokumen tersebut saat demonstrasi:

```text
HOTEL_ONLINE.md
→ menjelaskan aplikasi dan fitur

UKK_MANUAL.md
→ menjelaskan instalasi cloud dan deployment
```
