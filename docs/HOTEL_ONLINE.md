# Dokumentasi Oasis Hotel Online

Dokumen ini hanya menjelaskan aplikasi dan fitur Hotel Online. Konfigurasi Ubuntu, Docker, database container, Nginx, SSH, FTP, dan load balancing berada di dokumen terpisah:

- [`DOCKER_UKK_LENGKAP.md`](DOCKER_UKK_LENGKAP.md)
- [`UKK_MANUAL.md`](UKK_MANUAL.md)

---

## 1. Gambaran aplikasi

Oasis Hotel Online adalah aplikasi manajemen hotel berbasis Laravel yang menghubungkan:

- informasi hotel untuk pengunjung;
- pencarian dan pemesanan kamar;
- pembayaran reservasi;
- portal guest selama menginap;
- Room Service dan folio;
- reservasi fasilitas;
- katalog restoran dan venue;
- operasional Front Desk;
- pengelolaan kamar dan inventori;
- laporan operasional dan keuangan;
- pengelolaan pesan Contact;
- akses berbasis role.

## 2. Teknologi aplikasi

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.3+ dan Laravel 13 |
| Tampilan | Blade, Tailwind CSS, Alpine.js |
| Build frontend | Vite dan npm |
| Database | PostgreSQL atau MariaDB |
| Pembayaran reservasi | Midtrans Snap |
| Autentikasi | Laravel Breeze dan email verification |
| Proteksi form | CSRF dan reCAPTCHA jika key tersedia |
| PWA | Web App Manifest dan Service Worker |
| Laporan | PhpSpreadsheet |
| Pengujian | PHPUnit |

## 3. Role pengguna

Sistem memiliki empat role:

1. Guest;
2. Receptionist;
3. Manager;
4. Admin.

| Modul | Guest | Receptionist | Manager | Admin |
|---|---:|---:|---:|---:|
| Halaman publik | Ya | Ya | Ya | Ya |
| Booking kamar | Milik sendiri | Tidak | Lihat | Kelola |
| My Stay | Ya | Tidak | Tidak | Tidak |
| Room Service | Pesan | Proses tagihan | Lihat laporan | Kelola status |
| Check-in dan check-out | Tidak | Ya | Lihat | Lihat |
| Room Assignment | Tidak | Ya | Lihat | Lihat inventori |
| Folio | Receipt pribadi | Lihat dan bayar | Lihat dan cetak | Lihat dan cetak |
| Guest History | Profil sendiri | Lihat dan edit identitas | Lihat | Lihat |
| Venue restoran | Lihat | Tidak | CRUD | CRUD |
| Contact Inbox | Kirim pesan | Tidak | Read-only | Kelola |
| Reports | Tidak | Tidak | Ya | Ya |
| User management | Profil sendiri | Tidak | Read-only | Kelola |

Manager umumnya bersifat read-only untuk transaksi sensitif. Pengecualian saat ini adalah CRUD venue restoran.

---

## 4. Fitur publik

### Home

Home menampilkan pengenalan hotel, kamar unggulan, fasilitas, restoran, lokasi jika dikonfigurasi, FAQ, dan tombol menuju halaman utama lainnya.

### Rooms

Halaman Rooms menyediakan:

- daftar kamar dan tipe kamar;
- harga;
- kapasitas;
- fasilitas kamar;
- detail kamar;
- pemilihan tanggal;
- proses booking.

### Restaurant

Halaman Restaurant membaca menu dan venue dari database.

Data menu mencakup nama, kategori, deskripsi, harga, gambar, dan status ketersediaan.

Data venue mencakup nama, lokasi, deskripsi, jam operasional, kapasitas, gambar, status reservasi, dan status aktif.

### Facilities

Guest dapat melihat fasilitas dan membuat reservasi sesuai ketersediaan.

### Contact

Pesan Contact disimpan ke tabel `contact_messages`.

Admin dapat membaca, mengubah status, menghapus, membuka balasan email, dan menggunakan tombol Call. Manager hanya dapat membaca.

---

## 5. Autentikasi

Fitur autentikasi:

- Register;
- Login;
- Keep me signed in;
- Forgot Password;
- Reset Password;
- Verify Email;
- Confirm Password;
- Logout;
- reCAPTCHA jika dikonfigurasi.

## 6. Portal Guest

### Guest Dashboard

Menampilkan booking aktif, status kamar, jadwal menginap, dan shortcut menuju layanan guest.

### My Bookings

Guest dapat melihat booking, membayar reservasi pending melalui Midtrans, membatalkan booking sesuai ketentuan, serta membuka dan mencetak receipt.

### My Stay

Guest dapat melihat kamar, tanggal menginap, status stay, digital key saat checked-in, service request, dan receipt.

### Profile

Guest dapat memperbarui data profil sesuai field yang tersedia.

---

## 7. Room Service dan folio

Room Service adalah pesanan tambahan dan tidak otomatis termasuk harga kamar.

Alurnya:

```text
Guest memilih menu
→ Place order & add to folio
→ restaurant_orders dibuat
→ restaurant_order_details dibuat
→ payments dibuat sebagai pending
→ biaya muncul pada Folio dan Checkout
→ Receptionist menerima pembayaran
→ payment_status menjadi paid
→ Check-out dapat dilakukan
```

Room Service terhubung melalui:

```text
payments.booking_id
payments.restaurant_order_id
```

Perhitungan:

```text
Total Charges = biaya kamar + Room Service
Total Payments = seluruh pembayaran paid
Balance Due = Total Charges - Total Payments
```

Checkout ditahan selama `balance_due` lebih besar dari nol.

---

## 8. Receptionist

### Dashboard

Menampilkan occupancy, check-in, check-out, in-house guests, revenue, expected arrivals, room status, serta Needs Attention.

Needs Attention menyebutkan kamar atau booking yang perlu diproses dan menyediakan tombol menuju tindakan yang tepat.

### Room Assignment

Queue membaca booking pending dan confirmed. Sistem memeriksa tipe kamar, status kamar, konflik tanggal, pembayaran, dan status booking.

### Check-in

Check-in hanya dapat dilakukan untuk booking yang valid dan kamar yang siap.

### Payments

Receptionist memproses pembayaran cash, transfer, credit card, atau e-wallet.

### Check-out

Checkout hanya dapat dilakukan setelah folio lunas. Setelah checkout, booking menjadi `checked_out` dan kamar menjadi `maintenance`.

### Guest History

Receptionist dapat memperbarui nama, nomor telepon, nomor identitas, dan alamat guest. Email tidak diubah dari fitur ini karena menjadi penghubung akun dan histori.

### House Status

Status kamar final:

| Status | Arti |
|---|---|
| `available` | Siap digunakan |
| `occupied` | Sedang ditempati |
| `maintenance` | Belum siap atau sedang diperbaiki |

Status `dirty` tidak digunakan.

---

## 9. Admin dan Manager

### Rooms & Inventory

Menampilkan unit kamar, tipe, harga, kapasitas, status, dan detail tombol mata.

### Restaurant

Halaman staf memiliki tab:

```text
Orders
Venues
```

Tab Today’s Menu sudah dihapus.

Admin dan Manager dapat menambah, melihat, mengubah, mengaktifkan, menonaktifkan, dan menghapus venue sesuai aturan histori reservasi.

### Facilities

Admin mengelola fasilitas dan status reservasinya. Manager dapat melihat data sesuai hak akses.

### Finance dan Folio

Admin dan Manager dapat melihat serta mencetak folio. Pembayaran operasional dilakukan Receptionist.

### Reports

Reports menggunakan data database untuk occupancy, booking, revenue, top menu, top facility, restoran, kamar, dan export.

### Contact Inbox

Admin dapat mengelola status dan menghapus pesan. Manager read-only.

---

## 10. Fitur yang sengaja tidak digunakan

- Walk-in;
- Today’s Menu sebagai tab staf;
- status kamar `dirty`;
- folio dummy;
- charge dummy;
- link action `#`;
- pembayaran langsung Midtrans untuk Room Service.

## 11. Tabel database penting

| Tabel | Fungsi |
|---|---|
| `users` | Akun dan role |
| `guests` | Identitas guest |
| `room_types` | Tipe, harga, dan kapasitas |
| `rooms` | Unit dan status kamar |
| `bookings` | Reservasi kamar |
| `payments` | Pembayaran dan charge folio |
| `restaurant_menus` | Menu restoran |
| `restaurant_orders` | Pesanan restoran |
| `restaurant_order_details` | Item pesanan |
| `restaurant_venues` | Tempat makan |
| `restaurant_reservations` | Reservasi venue |
| `facilities` | Master fasilitas |
| `facility_bookings` | Reservasi fasilitas |
| `contact_messages` | Pesan Contact |

## 12. Setup lokal tanpa Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Seeder awal:

```bash
php artisan db:seed
php artisan db:seed --class=RestaurantMenuSeeder
php artisan db:seed --class=RestaurantVenueSeeder
```

## 13. Pengujian

```bash
php artisan test
npm run build
```

Test penting:

```bash
php artisan test --filter=RoomReceiptRouteTest
php artisan test --filter=OperationalContentRoutesTest
php artisan test --filter=StaffOperationsRoutesTest
php artisan test --filter=RolePathGuardTest
php artisan test --filter=RoomServicePaymentFlowTest
php artisan test --filter=EnumDatabaseCompatibilityTest
```
