# Manual Ringkas UKK Instalasi Komputasi Awan

Dokumen ini berfungsi sebagai ringkasan tugas, urutan praktik, dan checklist demonstrasi UKK.

Seluruh konfigurasi Docker yang harus diketik manual, termasuk Dockerfile, Compose, Nginx, database, migration, seeder, akses dari komputer penguji, SSH, FTP, dan bukti load balancing, berada di:

- [`DOCKER_UKK_LENGKAP.md`](DOCKER_UKK_LENGKAP.md)

Penjelasan aplikasi Hotel Online berada di:

- [`HOTEL_ONLINE.md`](HOTEL_ONLINE.md)

Repository utama sengaja tidak menyediakan file Docker siap pakai.

---

## 1. Kompetensi yang didemonstrasikan

1. Mengimplementasikan virtualisasi sesuai kebutuhan organisasi;
2. Mengimplementasikan topologi jaringan;
3. Mengembangkan perangkat lunak sesuai deployment environment.

## 2. Target infrastruktur

### VM1 deployment

- Ubuntu Server;
- Adapter NAT dan Bridge;
- hostname mengikuti nama siswa;
- aplikasi pada `/home/ujikom/hotel-online`;
- custom image aplikasi;
- tiga container web;
- satu container MariaDB;
- satu load balancer Nginx;
- web hanya `expose`;
- database `3306:3306`;
- load balancer `8080:80`;
- volume `volume-ujikom`;
- network `network-ujikom`;
- restart policy;
- bukti hostname web container berbeda.

### VM2 management

- Ubuntu Server;
- Adapter NAT dan Bridge;
- SSH Server passwordless dari VM1;
- FTP Server yang dapat diakses host;
- tiga container web sederhana;
- satu load balancer;
- tampilan hostname container berbeda.

## 3. Contoh IP kelompok pertama

| Perangkat | IP |
|---|---|
| Host | `172.20.3.2` |
| VM1 | `172.20.3.3` |
| VM2 | `172.20.3.4` |
| Gateway | `172.20.3.1` |
| DNS | `8.8.8.8` |
| Network | `172.20.3.0/24` |

Gunakan tabel IP yang diberikan penguji. Jangan memakai contoh ini untuk nomor absen yang berbeda.

## 4. Urutan praktik

```text
1. Buat dua VM Ubuntu
2. Aktifkan NAT dan Bridge
3. Atur hostname
4. Isi Netplan sesuai tabel IP
5. Uji ping VM1, VM2, gateway, dan internet
6. Instal Docker dan Docker Compose
7. Clone Hotel Online di VM1
8. Ketik konfigurasi Docker manual
9. Tambahkan tanda /instance untuk load balancing
10. Build custom image
11. Jalankan database
12. Jalankan migration satu kali
13. Isi database dengan seeder
14. Buat akun Admin
15. Jalankan tiga web dan load balancer
16. Akses aplikasi dari komputer penguji
17. Buktikan hostname container bergantian
18. Konfigurasi SSH passwordless VM1 ke VM2
19. Konfigurasi FTP VM2
20. Jalankan tiga web dan load balancer VM2
21. Ambil bukti command dan screenshot
```

## 5. Bukti yang harus disiapkan

### Virtualisasi

- tampilan dua VM;
- CPU, RAM, dan disk;
- Adapter NAT;
- Adapter Bridge;
- hostname masing-masing VM.

### Jaringan

```bash
ip -br address
ip route
ping -c 4 <IP_VM_LAIN>
ping -c 4 8.8.8.8
ping -c 4 google.com
```

### Docker VM1

```bash
docker --version
docker compose version
docker compose ps
docker images
docker network inspect network-ujikom
docker volume inspect volume-ujikom
```

### Database

```bash
php artisan migrate:status
```

Tunjukkan tabel dan data awal melalui MariaDB client atau halaman Admin.

### Load balancing VM1

```bash
for i in {1..10}; do
  curl -s http://localhost:8080/instance | grep -o 'Container: [^<]*'
done
```

Tunjukkan juga halaman browser:

```text
http://<IP_VM1>:8080/instance
```

### Management VM2

- SSH dari VM1 ke VM2 tanpa password;
- FTP login dari komputer host;
- upload file FTP;
- tiga container web;
- browser VM2 port 8080;
- hostname container bergantian.

## 6. Checklist sebelum demonstrasi

- [ ] VM1 dan VM2 dapat saling ping;
- [ ] internet NAT berfungsi;
- [ ] Docker daemon aktif;
- [ ] aplikasi berada pada direktori yang benar;
- [ ] custom image berhasil dibangun;
- [ ] tiga web container aktif;
- [ ] web tidak membuka port host;
- [ ] database membuka 3306;
- [ ] load balancer membuka 8080;
- [ ] volume bernama `volume-ujikom`;
- [ ] network bernama `network-ujikom`;
- [ ] restart policy aktif;
- [ ] migration berhasil;
- [ ] database memiliki data;
- [ ] aplikasi dapat dibuka dari komputer penguji;
- [ ] `/instance` menampilkan hostname berbeda;
- [ ] SSH passwordless berhasil;
- [ ] FTP dapat login dan upload;
- [ ] load balancing VM2 berhasil.

## 7. Aturan keamanan

- jangan commit `.env.docker`;
- jangan commit password database;
- jangan commit private key SSH;
- jangan menampilkan secret Midtrans atau email pada screenshot;
- gunakan `APP_DEBUG=false` saat demonstrasi;
- backup database sebelum menghapus volume.

## 8. Dokumen yang digunakan saat praktik

Gunakan urutan baca berikut:

1. `UKK_MANUAL.md` untuk melihat target dan checklist;
2. `DOCKER_UKK_LENGKAP.md` untuk mengetik semua file dan command;
3. `HOTEL_ONLINE.md` untuk menjelaskan fitur aplikasi kepada asesor.
