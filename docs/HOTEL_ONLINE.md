# Dokumentasi Oasis Hotel Online

Dokumen ini menjelaskan tujuan aplikasi, fitur setiap role, alur operasional hotel, pembayaran, folio, struktur data penting, serta contoh pengisian placeholder yang digunakan pada panduan UKK.

Panduan instalasi Ubuntu, Docker, Nginx, MariaDB, SSH, FTP, dan load balancing tetap berada di:

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

Aplikasi menggunakan Laravel Blade. Navigasi antarhalaman memuat halaman baru, tetapi posisi scroll sidebar staf disimpan agar tidak selalu kembali ke atas.

---

## 2. Teknologi utama

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.3+ dan Laravel 13 |
| Tampilan | Blade, Tailwind CSS, Alpine.js |
| Build frontend | Vite dan npm |
| Database cloud | PostgreSQL, termasuk Supabase |
| Database UKK Docker | MariaDB |
| Pembayaran reservasi | Midtrans Snap |
| Autentikasi | Laravel Breeze, email verification, reset password |
| Proteksi form | CSRF dan reCAPTCHA jika key tersedia |
| PWA | Web App Manifest dan Service Worker |
| Laporan | PhpSpreadsheet dan export laporan |
| Pengujian | PHPUnit melalui `php artisan test` |

Status enum kamar final adalah:

```text
available
occupied
maintenance
```

Status `dirty` tidak digunakan pada database final. Kamar yang baru selesai checkout masuk ke `maintenance` sampai dinyatakan siap kembali.

---

## 3. Role dan hak akses

Sistem memiliki empat role:

1. Guest;
2. Receptionist;
3. Manager;
4. Admin.

### 3.1 Ringkasan hak akses

| Modul | Guest | Receptionist | Manager | Admin |
|---|---:|---:|---:|---:|
| Halaman publik | Ya | Ya | Ya | Ya |
| Booking kamar pribadi | Ya | Tidak | Lihat | Kelola |
| My Stay | Ya | Tidak | Tidak | Tidak |
| Room Service | Pesan | Proses melalui folio | Lihat laporan | Kelola status |
| Check-in dan check-out | Tidak | Ya | Lihat | Lihat |
| Room Assignment | Tidak | Ya | Lihat | Lihat dan kelola inventori |
| Folio | Receipt pribadi | Lihat dan proses pembayaran | Lihat dan cetak | Lihat dan cetak |
| Guest History | Profil pribadi | Lihat dan edit identitas | Lihat | Lihat |
| Venue restoran | Lihat venue aktif | Tidak dikelola | CRUD | CRUD |
| Contact Inbox | Kirim pesan | Tidak | Read-only | Update status dan hapus |
| Reports | Tidak | Tidak | Ya | Ya |
| User management | Profil sendiri | Tidak | Read-only | Kelola akun |

Manager umumnya read-only untuk transaksi sensitif. Pengecualian saat ini adalah CRUD venue restoran.

---

## 4. Fitur publik

### 4.1 Home

Home menampilkan:

- pengenalan hotel;
- kamar unggulan;
- fasilitas;
- restoran;
- lokasi hotel jika koordinat sudah dikonfigurasi;
- FAQ;
- tombol menuju Rooms, Restaurant, Facilities, dan Contact.

Form Check Room tidak ditempatkan di Home. Pengecekan tanggal dilakukan di halaman Rooms.

### 4.2 Rooms

Halaman Rooms menyediakan:

- daftar kamar;
- tipe kamar;
- harga;
- kapasitas;
- fasilitas kamar;
- detail kamar;
- tanggal check-in dan check-out;
- proses booking.

### 4.3 Restaurant

Halaman Restaurant membaca data asli dari database.

Data menu memuat:

- nama;
- kategori;
- deskripsi;
- harga;
- gambar;
- status tersedia.

Data venue memuat:

- nama venue;
- lokasi;
- deskripsi;
- jam buka dan tutup;
- kapasitas;
- gambar;
- status reservasi;
- status aktif.

### 4.4 Facilities

Halaman Facilities menampilkan fasilitas aktif dan memungkinkan guest membuat reservasi sesuai ketersediaan.

### 4.5 Contact

Form Contact menyimpan pesan ke tabel `contact_messages`.

Admin dapat:

- melihat pesan;
- mengubah status `new`, `in_progress`, atau `resolved`;
- menghapus pesan;
- membuka balasan email;
- membuka panggilan telepon.

Manager hanya dapat membaca pesan.

---

## 5. Autentikasi

Fitur autentikasi meliputi:

- Register;
- Login;
- Keep me signed in;
- Forgot Password;
- Reset Password;
- Verify Email;
- Confirm Password;
- Logout;
- reCAPTCHA jika key tersedia.

Checkbox Keep me signed in dapat diklik melalui kotak maupun teks labelnya.

---

## 6. Portal Guest

### 6.1 Guest Dashboard

Menampilkan:

- ringkasan booking aktif;
- status kamar;
- jadwal menginap;
- shortcut ke My Bookings, My Stay, Room Service, Restaurant Orders, dan Facilities.

### 6.2 My Bookings

Guest dapat:

- melihat booking;
- membayar reservasi pending melalui Midtrans;
- membatalkan booking yang masih memenuhi ketentuan;
- membuka receipt untuk status confirmed, checked-in, dan checked-out;
- mencetak receipt.

### 6.3 My Stay

Guest dapat melihat:

- kamar;
- tanggal menginap;
- status check-in atau check-out;
- digital key saat checked-in;
- service request;
- receipt setelah checkout.

### 6.4 Profile

Guest dapat memperbarui data profil sesuai field yang tersedia.

---

## 7. Room Service dan folio

Room Service bukan makanan gratis yang otomatis termasuk pada harga kamar.

Aturannya:

- harga kamar membayar akomodasi;
- sarapan hanya termasuk jika paket kamar menyebutkannya;
- Room Service adalah pesanan tambahan;
- guest tidak perlu membayar saat memesan;
- biaya otomatis masuk ke folio kamar;
- pembayaran dilakukan bersama tagihan akhir melalui Front Desk.

Alur:

```text
Guest memilih menu
    ↓
Place order & add to folio
    ↓
restaurant_orders dibuat
    ↓
restaurant_order_details dibuat
    ↓
payments dibuat sebagai pending
    ↓
biaya muncul pada Folio dan Checkout
    ↓
Receptionist menyelesaikan pembayaran
    ↓
payment_status menjadi paid
    ↓
Check-out dapat dilakukan
```

Record Room Service terhubung melalui:

```text
payments.booking_id
payments.restaurant_order_id
```

Checkout harus ditahan jika `balance_due` masih lebih besar dari nol.

---

## 8. Front Desk dan Receptionist

### 8.1 Dashboard

Receptionist Dashboard menampilkan:

- occupancy;
- check-in hari ini;
- check-out hari ini;
- in-house guests;
- revenue hari ini;
- expected arrivals;
- room status;
- Needs Attention.

Needs Attention menyebutkan booking atau kamar yang perlu diproses, bukan hanya angka.

### 8.2 Room Assignment

Queue membaca booking pending dan confirmed yang masih membutuhkan konfirmasi kamar.

Sistem memeriksa:

- tipe kamar;
- status kamar;
- konflik tanggal;
- status pembayaran;
- status booking.

### 8.3 Check-in

Check-in hanya dilakukan pada booking yang valid dan kamar yang siap.

### 8.4 Payments

Halaman Payments menghitung:

```text
Total Charges = harga kamar + Room Service
Total Payments = pembayaran yang sudah paid
Balance Due = Total Charges - Total Payments
```

Pembayaran Front Desk dapat menggunakan:

- cash;
- transfer;
- credit card;
- e-wallet.

### 8.5 Check-out

Check-out hanya dapat diselesaikan jika folio sudah lunas.

Setelah checkout:

- booking menjadi `checked_out`;
- kamar menjadi `maintenance`;
- receipt tetap dapat dibuka;
- kamar harus dinyatakan `available` kembali melalui proses operasional.

### 8.6 Guest History

Receptionist dapat melihat riwayat guest dan memperbarui:

- nama;
- nomor telepon;
- nomor identitas;
- alamat.

Email tidak diubah dari halaman ini karena digunakan sebagai penghubung akun dan histori.

### 8.7 House Status

Status kamar yang digunakan:

| Status | Arti |
|---|---|
| `available` | Kamar siap digunakan |
| `occupied` | Kamar sedang ditempati |
| `maintenance` | Kamar belum siap atau sedang diperbaiki |

---

## 9. Admin dan Manager

### 9.1 Rooms & Inventory

Menyediakan:

- daftar kamar;
- tipe kamar;
- harga;
- kapasitas;
- status;
- detail melalui tombol mata;
- pengelolaan inventori sesuai hak role.

### 9.2 Restaurant

Halaman Restaurant staf memiliki tab:

```text
Orders
Venues
```

Tab Today’s Menu sudah dihapus.

Admin dan Manager dapat mengelola Venue langsung di tab Venues tanpa pindah halaman.

CRUD Venue meliputi:

- Create;
- Read;
- Update;
- Delete;
- aktif atau nonaktif;
- reservasi aktif atau nonaktif;
- urutan tampil.

Venue yang sudah memiliki histori reservasi sebaiknya dinonaktifkan, bukan dihapus.

### 9.3 Reports

Reports menampilkan data nyata dari database:

- occupancy;
- booking;
- revenue;
- top menu;
- top facility;
- ringkasan restoran;
- ringkasan kamar;
- export Excel dan PDF.

### 9.4 Folio

Admin dan Manager dapat membuka serta mencetak folio. Proses pembayaran tetap dilakukan Receptionist.

---

## 10. Fitur yang sengaja tidak digunakan

Fitur berikut sudah dihapus atau tidak digunakan:

- Walk-in;
- Today’s Menu sebagai tab staf;
- status kamar `dirty`;
- data folio dummy;
- charge dummy;
- link action `#`;
- pembayaran langsung Midtrans untuk Room Service.

Jangan menambahkan kembali tombol menuju route tersebut tanpa kebutuhan operasional baru.

---

# BAGIAN CONTOH PLACEHOLDER UKK

## 11. Aturan menggunakan contoh

Nilai pada bagian ini hanya simulasi agar mudah mengikuti panduan.

Saat penguji memberikan IP, interface, gateway, atau DNS yang berbeda, ganti nilai contoh tersebut. Konfigurasi Docker tetap menggunakan nama service dan tidak memakai IP VM.

Contoh simulasi yang digunakan:

| Placeholder | Contoh isi | Keterangan |
|---|---|---|
| `<NAMA_SISWA>` | `budi` | Gunakan nama singkat tanpa spasi |
| `<USERNAME>` | `ujikom` | Username Ubuntu |
| `<NAMA_APLIKASI>` | `hotel-online` | Nama folder project |
| `<PROJECT_PATH>` | `/home/ujikom/hotel-online` | Lokasi project Laravel |
| `<IP_HOST>` | `172.20.3.2` | IP komputer penguji |
| `<IP_VM1>` | `172.20.3.3` | IP deployment server |
| `<IP_VM2>` | `172.20.3.4` | IP management server |
| `<IP_VM>/<PREFIX>` | `172.20.3.3/24` | IP dan prefix Netplan VM1 |
| `<GATEWAY>` | `172.20.3.1` | Gateway jaringan Bridge |
| `<DNS>` | `8.8.8.8` | DNS contoh |
| `<INTERFACE_NAT>` | `enp0s3` | Contoh saja, cek dengan `ip -br address` |
| `<INTERFACE_BRIDGE>` | `enp0s8` | Contoh saja, cek dengan `ip -br address` |
| `<DB_DATABASE>` | `oasis_hotel` | Nama database MariaDB |
| `<DB_USERNAME>` | `oasis_hotel` | User database |
| `<DB_PASSWORD>` | `UkkHotel123!` | Contoh password latihan |
| `<MARIADB_ROOT_PASSWORD>` | `RootUkk123!` | Contoh password root latihan |
| `<APP_KEY>` | `base64:HASIL_PERINTAH_KEY` | Dibuat dengan Artisan atau OpenSSL |

Hostname contoh:

```text
VM1: budi-deployment
VM2: budi-management
```

Gunakan tanda hubung pada hostname Linux agar lebih aman daripada underscore.

---

## 12. Contoh mengganti placeholder pada command

Command panduan:

```bash
cd /home/<USERNAME>/<NAMA_APLIKASI>
```

Contoh terisi:

```bash
cd /home/ujikom/hotel-online
```

Command panduan:

```bash
ping <IP_VM2>
```

Contoh terisi:

```bash
ping 172.20.3.4
```

Command panduan:

```bash
curl http://<IP_VM1>:8080
```

Contoh terisi:

```bash
curl http://172.20.3.3:8080
```

Command SSH panduan:

```bash
ssh <USERNAME>@<IP_VM2>
```

Contoh terisi:

```bash
ssh ujikom@172.20.3.4
```

Command FTP panduan:

```bash
ftp <IP_VM2>
```

Contoh terisi:

```bash
ftp 172.20.3.4
```

Akses browser panduan:

```text
http://<IP_VM1>:8080
```

Contoh terisi:

```text
http://172.20.3.3:8080
```

---

## 13. Contoh Netplan dengan placeholder

File contoh:

```text
/etc/netplan/01-ukk.yaml
```

Template:

```yaml
network:
  version: 2
  renderer: networkd
  ethernets:
    <INTERFACE_NAT>:
      dhcp4: true
    <INTERFACE_BRIDGE>:
      dhcp4: false
      addresses:
        - <IP_VM>/<PREFIX>
      routes:
        - to: default
          via: <GATEWAY>
      nameservers:
        addresses:
          - <DNS>
```

Contoh terisi untuk VM1:

```yaml
network:
  version: 2
  renderer: networkd
  ethernets:
    enp0s3:
      dhcp4: true
    enp0s8:
      dhcp4: false
      addresses:
        - 172.20.3.3/24
      routes:
        - to: default
          via: 172.20.3.1
      nameservers:
        addresses:
          - 8.8.8.8
```

Contoh terisi untuk VM2 hanya mengganti IP Bridge:

```yaml
network:
  version: 2
  renderer: networkd
  ethernets:
    enp0s3:
      dhcp4: true
    enp0s8:
      dhcp4: false
      addresses:
        - 172.20.3.4/24
      routes:
        - to: default
          via: 172.20.3.1
      nameservers:
        addresses:
          - 8.8.8.8
```

Sebelum menyalin contoh, periksa interface:

```bash
ip -br address
ip route
```

Terapkan dengan:

```bash
sudo netplan try
sudo netplan apply
```

Pengujian VM1:

```bash
ping -c 4 172.20.3.4
ping -c 4 8.8.8.8
ping -c 4 google.com
```

---

## 14. Contoh `.env.docker`

Template tetap menggunakan hostname service `database`, bukan IP VM.

```env
APP_NAME="Oasis Hotel"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:HASIL_PERINTAH_KEY
APP_URL=http://172.20.3.3:8080

DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=oasis_hotel
DB_USERNAME=oasis_hotel
DB_PASSWORD=UkkHotel123!

MARIADB_ROOT_PASSWORD=RootUkk123!
RUN_MIGRATIONS=false
```

Hal penting:

```text
DB_HOST=database
```

Jangan menggantinya menjadi:

```text
localhost
127.0.0.1
172.20.3.3
```

Cara membuat APP_KEY menggunakan container web setelah image berhasil dibangun:

```bash
docker compose run --rm web php artisan key:generate --show
```

Contoh hasil:

```text
base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX=
```

Salin seluruh hasil tersebut ke `APP_KEY`.

---

## 15. Contoh environment database pada Docker Compose

```env
DB_DATABASE=oasis_hotel
DB_USERNAME=oasis_hotel
DB_PASSWORD=UkkHotel123!
MARIADB_ROOT_PASSWORD=RootUkk123!
```

Service database tetap bernama:

```text
database
```

Service aplikasi tetap bernama:

```text
web
```

Network dan volume UKK:

```text
network-ujikom
volume-ujikom
```

Port:

```text
Load balancer: 8080:80
Database:      3306:3306
Web:           expose 80
```

---

## 16. Contoh deployment VM1 yang sudah terisi

```bash
cd /home/ujikom/hotel-online
cp .env.docker.example .env
nano .env
chmod +x entrypoint.sh

docker compose build
docker compose up -d database

docker compose run --rm \
  -e RUN_MIGRATIONS=true \
  web php artisan migrate --force

docker compose up -d --build --scale web=3
```

Pemeriksaan:

```bash
docker compose ps
docker compose logs
docker compose logs web
docker compose logs loadbalancer
docker network inspect network-ujikom
docker volume inspect volume-ujikom
```

Pengujian:

```bash
curl http://localhost:8080
curl http://172.20.3.3:8080

for i in {1..10}; do
  curl http://localhost:8080/instance
  echo
done
```

Akses komputer penguji:

```text
http://172.20.3.3:8080
```

---

## 17. Contoh SSH passwordless

Pada VM2:

```bash
sudo apt update
sudo apt install -y openssh-server
sudo systemctl enable --now ssh
sudo systemctl status ssh
```

Pada VM1:

```bash
ssh-keygen -t ed25519
ssh-copy-id ujikom@172.20.3.4
ssh ujikom@172.20.3.4
```

Saat `ssh-keygen` meminta lokasi file dan passphrase untuk latihan UKK, tekan Enter sesuai arahan penguji.

Bukti berhasil:

```text
VM1 dapat masuk ke VM2 tanpa mengetik password akun VM2.
```

---

## 18. Contoh FTP VM2

Pada VM2:

```bash
sudo apt update
sudo apt install -y vsftpd
sudo systemctl enable --now vsftpd
sudo systemctl status vsftpd
```

Contoh pengujian dari komputer host:

```bash
ftp 172.20.3.4
```

Login contoh:

```text
Username: ujikom
Password: password akun Ubuntu VM2
```

---

## 19. Placeholder yang tidak boleh dimasukkan ke Docker

IP berikut hanya digunakan untuk akses dan pengujian:

```text
<IP_HOST>
<IP_VM1>
<IP_VM2>
<GATEWAY>
<DNS>
```

IP tersebut tidak boleh ditulis sebagai alamat container pada:

- Dockerfile;
- `docker-compose.yml`;
- `entrypoint.sh`;
- `nginx.conf`.

Komunikasi container harus menggunakan:

```text
web
database
```

Contoh Nginx:

```nginx
upstream laravel_web {
    server web:80;
}
```

Contoh Laravel database:

```env
DB_HOST=database
```

---

## 20. Database utama

Tabel penting aplikasi:

| Tabel | Fungsi |
|---|---|
| `users` | Akun dan role |
| `guests` | Identitas guest |
| `room_types` | Tipe, harga, kapasitas kamar |
| `rooms` | Unit dan status kamar |
| `bookings` | Reservasi kamar |
| `payments` | Pembayaran dan charge folio |
| `restaurant_menus` | Menu restoran |
| `restaurant_orders` | Header pesanan restoran |
| `restaurant_order_details` | Item pesanan |
| `restaurant_venues` | Tempat makan |
| `restaurant_reservations` | Reservasi venue |
| `facilities` | Master fasilitas |
| `facility_bookings` | Reservasi fasilitas |
| `contact_messages` | Pesan Contact |

---

## 21. Seeder

Seeder utama yang perlu dijalankan bila database kosong:

```bash
php artisan db:seed --class=RestaurantMenuSeeder
php artisan db:seed --class=RestaurantVenueSeeder
```

Untuk Docker:

```bash
docker compose exec web php artisan db:seed --class=RestaurantMenuSeeder
docker compose exec web php artisan db:seed --class=RestaurantVenueSeeder
```

Jalankan seeder dari satu container saja.

---

## 22. Pengembangan lokal

```bash
composer install
npm install
php artisan migrate
npm run build
php artisan serve
```

Mode development:

```bash
composer run dev
```

---

## 23. Pemeriksaan aplikasi

```bash
php artisan route:list
php artisan view:cache
php artisan view:clear
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

---

## 24. Checklist demonstrasi fitur

### Guest

- Register dan login;
- verify email;
- booking kamar;
- pembayaran reservasi;
- My Bookings;
- My Stay;
- receipt;
- Room Service masuk folio;
- reservasi fasilitas;
- Contact.

### Receptionist

- Dashboard;
- Needs Attention;
- Room Assignment;
- Check-in;
- Folio;
- pembayaran Room Service;
- Check-out;
- Guest History;
- House Status.

### Admin dan Manager

- Dashboard;
- Rooms & Inventory;
- detail tombol mata;
- Restaurant Orders;
- CRUD Venue;
- Facilities;
- Finance;
- Folio;
- Reports;
- Contact Inbox.

### Infrastruktur UKK

- VM1 dan VM2 saling ping;
- Docker service aktif;
- tiga container web;
- MariaDB aktif;
- network `network-ujikom`;
- volume `volume-ujikom`;
- load balancer port 8080;
- hostname container bergantian;
- SSH passwordless;
- FTP dapat login dan upload.

---

## 25. Ringkasan cara mengikuti placeholder

Gunakan pola berikut setiap kali melihat placeholder:

```text
Baca placeholder
→ lihat nilai dari penguji
→ ganti hanya nilai tersebut
→ jangan mengubah nama service Docker
→ jalankan command pemeriksaan
```

Contoh:

```text
<IP_VM1> diberikan penguji sebagai 10.10.10.11
```

Maka:

```text
http://<IP_VM1>:8080
```

menjadi:

```text
http://10.10.10.11:8080
```

Tetapi ini tetap tidak berubah:

```env
DB_HOST=database
```

Dan ini tetap tidak berubah:

```nginx
server web:80;
```
