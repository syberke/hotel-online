# Tutorial Manual UKK Instalasi Komputasi Awan

Panduan ini dibuat untuk latihan UKK klaster **Instalasi Komputasi Awan** menggunakan aplikasi **Oasis Hotel Online**.

Seluruh konfigurasi utama dilakukan secara manual. Saat latihan utama, jangan langsung menjalankan `deploy.sh`. Tujuannya agar setiap tahap dapat diketik ulang, diuji, dan dijelaskan kepada asesor.

> Rekomendasi latihan: gunakan Ubuntu Server 24.04 LTS 64-bit pada kedua VM. Ubuntu 22.04 LTS juga dapat digunakan, tetapi nama interface dan beberapa tampilan installer dapat berbeda.

---

## 1. Target akhir UKK

Lingkungan yang dibangun terdiri dari dua mesin virtual Ubuntu Server.

### VM1: deployment

- hostname: `namasiswa-deployment`;
- dua adapter jaringan, NAT dan Bridged Adapter;
- aplikasi berada di `/home/ujikom/hotel-online`;
- custom image dibuat dari `Dockerfile`;
- tiga container Laravel, yaitu `web1`, `web2`, dan `web3`;
- satu container MariaDB;
- satu container Nginx sebagai load balancer;
- load balancer diakses melalui port `8080:80`;
- container web hanya memakai `expose`, bukan `ports`;
- database memakai port `3306:3306` sesuai ketentuan UKK;
- database memakai named volume `volume-ujikom`;
- semua container memakai network `network-ujikom`;
- semua service memakai restart policy;
- setiap web node memiliki identitas berbeda untuk membuktikan load balancing.

### VM2: management

- hostname: `namasiswa-management`;
- dua adapter jaringan, NAT dan Bridged Adapter;
- OpenSSH Server;
- SSH dari VM1 ke VM2 tanpa mengetik password;
- FTP Server yang dapat diakses dari komputer host;
- tiga container Nginx dengan tampilan berbeda;
- satu container Nginx sebagai load balancer;
- load balancer VM2 membagi request ke tiga container web.

---

## 2. Pemetaan ketentuan UKK

| Ketentuan | Implementasi |
|---|---|
| Dua VM Ubuntu | VM1 deployment dan VM2 management |
| Dua adapter | Adapter 1 NAT, Adapter 2 Bridged Adapter |
| VM saling terhubung | Pengujian `ping` dua arah |
| Custom image | Laravel dibangun dari `Dockerfile` |
| Tiga web container | `web1`, `web2`, dan `web3` |
| Database | MariaDB 11.4 |
| Port database | `3306:3306` |
| Volume | `volume-ujikom` |
| Network | `network-ujikom` |
| Load balancer | Nginx pada port `8080:80` |
| Perbedaan web node | `APP_NODE_NAME` dan `APP_NODE_COLOR` |
| SSH tanpa password | SSH key ED25519 dari VM1 ke VM2 |
| FTP | vsftpd pada VM2 |
| Tiga web VM2 | `site1`, `site2`, dan `site3` |
| Pengujian | `ping`, `curl`, browser, Docker CLI, SSH, dan FTP client |

---

## 3. Apa saja yang harus diinstal

### 3.1 Pada komputer host Windows

Siapkan:

1. Oracle VirtualBox;
2. ISO Ubuntu Server 24.04 LTS 64-bit;
3. browser Chrome atau Edge;
4. PowerShell;
5. FileZilla Client atau WinSCP untuk pengujian FTP;
6. Visual Studio Code bersifat opsional untuk membaca dokumentasi.

Git, PHP, Composer, Node.js, MariaDB, Apache, dan Nginx **tidak wajib diinstal di Windows** untuk praktik deployment ini. Semua layanan aplikasi dijalankan di dalam VM dan container.

### 3.2 Pada kedua VM

Paket dasar:

- `ca-certificates`, untuk validasi sertifikat HTTPS;
- `curl`, untuk mengambil file dan menguji HTTP;
- `git`, untuk mengambil source code;
- `openssl`, untuk membuat `APP_KEY`;
- `nano`, editor teks terminal;
- `unzip` dan `zip`, utilitas arsip;
- `jq`, membaca JSON dari terminal;
- `tree`, melihat struktur folder;
- `iputils-ping`, pengujian jaringan;
- `dnsutils`, pengujian DNS;
- `net-tools`, alat jaringan tambahan;
- `ufw`, firewall Ubuntu;
- `openssh-client`, perintah `ssh`, `scp`, dan `ssh-copy-id`.

Paket Docker:

- `docker-ce`, Docker Engine;
- `docker-ce-cli`, command line Docker;
- `containerd.io`, runtime container;
- `docker-buildx-plugin`, builder image;
- `docker-compose-plugin`, perintah `docker compose`.

### 3.3 Tambahan pada VM2

- `openssh-server`, server SSH;
- `vsftpd`, server FTP.

### 3.4 Yang tidak perlu diinstal langsung pada Ubuntu host

Untuk tutorial ini, jangan menginstal paket berikut pada host kecuali asesor secara khusus meminta:

- PHP;
- Composer;
- Node.js dan npm;
- Apache host;
- Nginx host;
- MariaDB atau MySQL host.

Alasannya:

- Node.js tersedia pada tahap `frontend` Dockerfile;
- Composer tersedia pada tahap `vendor` Dockerfile;
- PHP dan Apache tersedia pada image runtime;
- MariaDB dan Nginx dijalankan sebagai container.

Cara menjelaskan kepada asesor:

> Host hanya membutuhkan Docker dan alat administrasi. Dependency aplikasi dimasukkan ke custom image agar deployment konsisten dan tidak bergantung pada instalasi PHP, Composer, atau Node.js pada host.

---

## 4. Lembar variabel sebelum mulai

Isi sesuai nomor absen pada tabel UKK.

| Variabel | Isi |
|---|---|
| Nama siswa | `<NAMA_SISWA>` |
| Username Ubuntu | `ujikom` |
| IP host | `<IP_HOST>` |
| IP VM1 | `<IP_VM1>` |
| IP VM2 | `<IP_VM2>` |
| Network | `172.20.3.0/24` |
| Gateway | `172.20.3.1` |
| DNS | `8.8.8.8` |
| Interface NAT | `<INTERFACE_NAT>` |
| Interface bridge | `<INTERFACE_BRIDGE>` |

Contoh nomor absen 1:

```text
IP host : 172.20.3.2
IP VM1  : 172.20.3.3
IP VM2  : 172.20.3.4
```

Jangan memakai contoh tersebut jika nomor absen berbeda.

---

## 5. Topologi

```text
Komputer Host
     |
     | Bridged Network 172.20.3.0/24
     |
     +--------------------------------+
     |                                |
     v                                v
VM1 deployment                   VM2 management
<IP_VM1>                         <IP_VM2>
     |                                |
     | :8080                          | :8080
     v                                v
Nginx Load Balancer              Nginx Load Balancer
  |       |       |                |       |       |
 web1    web2    web3            site1   site2   site3
  |       |       |
  +-------+-------+
          |
       MariaDB
```

Fungsi jaringan:

- NAT menyediakan internet untuk `apt`, Git, dan pull image;
- Bridged Adapter menghubungkan host, VM1, dan VM2 pada jaringan yang sama;
- Docker bridge network menghubungkan container melalui nama service.

---

# BAGIAN A: MEMBUAT VM DARI NOL

## 6. Membuat dua VM di VirtualBox

Lakukan untuk VM1 dan VM2.

1. Klik **New**.
2. Pilih ISO Ubuntu Server.
3. Type: `Linux`.
4. Version: `Ubuntu (64-bit)`.
5. RAM minimum 2 GB. Untuk VM1 disarankan 4 GB.
6. CPU minimum 2 core untuk VM1 dan 1 sampai 2 core untuk VM2.
7. Disk minimum 25 GB, dynamically allocated.
8. Buka **Settings > Network**.
9. Adapter 1:
   - Enable Network Adapter;
   - Attached to: `NAT`;
   - Cable Connected aktif.
10. Adapter 2:
   - Enable Network Adapter;
   - Attached to: `Bridged Adapter`;
   - pilih kartu Wi-Fi atau Ethernet host yang aktif;
   - Cable Connected aktif.

### 6.1 Instal Ubuntu Server

Saat installer berjalan:

1. pilih bahasa;
2. pilih keyboard layout;
3. pilih `Ubuntu Server`;
4. biarkan interface NAT memperoleh DHCP;
5. proxy dikosongkan jika tidak digunakan;
6. gunakan mirror default;
7. gunakan seluruh virtual disk;
8. buat user:

```text
Your name   : Uji Kompetensi
Server name : sementara, akan diganti
Username    : ujikom
Password    : password latihan
```

9. OpenSSH boleh tidak dipilih karena akan dipasang manual;
10. jangan pilih snap tambahan;
11. selesaikan instalasi dan reboot;
12. keluarkan ISO jika installer terbuka kembali.

Setelah login:

```bash
whoami
hostnamectl
lsb_release -a
uname -m
```

Hasil yang diharapkan:

- user adalah `ujikom`;
- arsitektur umumnya `x86_64`;
- Ubuntu 24.04 atau 22.04 LTS.

---

## 7. Mengatur hostname

Linux hostname sebaiknya memakai tanda hubung, bukan underscore.

### VM1

```bash
sudo hostnamectl set-hostname namasiswa-deployment
hostnamectl
```

### VM2

```bash
sudo hostnamectl set-hostname namasiswa-management
hostnamectl
```

Logout dan login kembali agar prompt menampilkan hostname baru:

```bash
exit
```

Tambahkan pemetaan kedua VM pada `/etc/hosts` di VM1 dan VM2:

```bash
sudo nano /etc/hosts
```

Tambahkan:

```text
<IP_VM1> namasiswa-deployment
<IP_VM2> namasiswa-management
```

---

## 8. Mengidentifikasi interface jaringan

Jalankan pada masing-masing VM:

```bash
ip -br link
ip -br address
ip route
```

Umumnya pada VirtualBox:

```text
enp0s3 = NAT
enp0s8 = Bridged Adapter
```

Nama interface dapat berbeda. Jangan menyalin nama interface tanpa memeriksa hasil perintah.

---

## 9. Mengatur IP statis dengan Netplan

Cadangkan konfigurasi:

```bash
sudo cp -a /etc/netplan /etc/netplan.backup
ls -la /etc/netplan
```

Lihat file bawaan:

```bash
sudo cat /etc/netplan/*.yaml
```

Buat file:

```bash
sudo nano /etc/netplan/01-ukk.yaml
```

### 9.1 Konfigurasi VM1

Ganti interface dan IP.

```yaml
network:
  version: 2
  renderer: networkd
  ethernets:
    enp0s3:
      dhcp4: true
      dhcp4-overrides:
        route-metric: 100
    enp0s8:
      dhcp4: false
      addresses:
        - <IP_VM1>/24
      routes:
        - to: default
          via: 172.20.3.1
          metric: 200
      nameservers:
        addresses:
          - 8.8.8.8
```

### 9.2 Konfigurasi VM2

```yaml
network:
  version: 2
  renderer: networkd
  ethernets:
    enp0s3:
      dhcp4: true
      dhcp4-overrides:
        route-metric: 100
    enp0s8:
      dhcp4: false
      addresses:
        - <IP_VM2>/24
      routes:
        - to: default
          via: 172.20.3.1
          metric: 200
      nameservers:
        addresses:
          - 8.8.8.8
```

Perbaiki permission dan terapkan:

```bash
sudo chmod 600 /etc/netplan/01-ukk.yaml
sudo netplan generate
sudo netplan try
sudo netplan apply
ip -br address
ip route
```

Metric 100 membuat internet NAT lebih diprioritaskan daripada gateway bridge bermetric 200.

### 9.3 Jika bridge tidak memiliki gateway

Pada beberapa laboratorium, bridge hanya dipakai untuk komunikasi lokal. Jika gateway `172.20.3.1` tidak tersedia, hapus bagian `routes` pada interface bridge. NAT tetap menjadi jalur internet.

---

## 10. Menguji jaringan sebelum instalasi

### Dari VM1

```bash
ping -c 4 8.8.8.8
ping -c 4 google.com
ping -c 4 <IP_VM2>
```

### Dari VM2

```bash
ping -c 4 <IP_VM1>
```

### Dari host Windows

```powershell
ping <IP_VM1>
ping <IP_VM2>
```

Jangan lanjut ke Docker sebelum:

- kedua VM bisa mengakses internet;
- VM1 dan VM2 saling ping;
- host bisa ping kedua VM.

---

# BAGIAN B: INSTALASI SOFTWARE DARI VM FRESH

## 11. Update Ubuntu dan instal paket dasar

Jalankan pada VM1 dan VM2:

```bash
sudo apt update
sudo apt upgrade -y
sudo apt install -y \
  ca-certificates \
  curl \
  git \
  openssl \
  nano \
  unzip \
  zip \
  jq \
  tree \
  iputils-ping \
  dnsutils \
  net-tools \
  ufw \
  openssh-client
```

Periksa:

```bash
git --version
curl --version | head -n 1
openssl version
ssh -V
```

Penjelasan paket penting:

| Paket | Fungsi |
|---|---|
| Git | mengambil source aplikasi |
| curl | menguji HTTP dan mengambil key Docker |
| OpenSSL | membuat application key |
| OpenSSH client | SSH dari VM1 ke VM2 |
| UFW | mengatur port firewall |
| jq | membaca hasil JSON jika diperlukan |
| ping dan dnsutils | menguji IP dan DNS |

Jika kernel ikut diperbarui, reboot:

```bash
sudo reboot
```

Setelah VM hidup, login dan cek:

```bash
uname -r
```

---

## 12. Instal Docker Engine dan Docker Compose secara manual

Lakukan pada VM1 dan VM2.

### 12.1 Hapus paket yang berpotensi konflik

Pada VM baru biasanya tidak ada paket konflik. Perintah berikut tetap aman dijalankan:

```bash
for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do
  sudo apt remove -y "$pkg" 2>/dev/null || true
done
```

### 12.2 Tambahkan GPG key resmi Docker

```bash
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc
```

Periksa:

```bash
ls -l /etc/apt/keyrings/docker.asc
```

### 12.3 Tambahkan repository Docker secara dinamis

Perintah ini membaca codename Ubuntu dan arsitektur secara otomatis:

```bash
. /etc/os-release
UBUNTU_SUITE="${UBUNTU_CODENAME:-$VERSION_CODENAME}"
ARCHITECTURE="$(dpkg --print-architecture)"

echo "Suite: $UBUNTU_SUITE"
echo "Architecture: $ARCHITECTURE"
```

Buat source:

```bash
sudo tee /etc/apt/sources.list.d/docker.sources > /dev/null <<EOF
Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: ${UBUNTU_SUITE}
Components: stable
Architectures: ${ARCHITECTURE}
Signed-By: /etc/apt/keyrings/docker.asc
EOF
```

Periksa isi:

```bash
cat /etc/apt/sources.list.d/docker.sources
```

### 12.4 Instal paket Docker

```bash
sudo apt update
sudo apt install -y \
  docker-ce \
  docker-ce-cli \
  containerd.io \
  docker-buildx-plugin \
  docker-compose-plugin
```

### 12.5 Aktifkan Docker saat boot

```bash
sudo systemctl enable --now docker
sudo systemctl enable --now containerd
sudo systemctl status docker --no-pager
```

Status harus `active (running)`.

### 12.6 Beri akses Docker kepada user ujikom

```bash
sudo usermod -aG docker "$USER"
```

Pilihan pertama, logout lalu login kembali:

```bash
exit
```

Pilihan kedua untuk terminal latihan:

```bash
newgrp docker
```

Periksa grup:

```bash
id
```

Harus terdapat grup `docker`.

### 12.7 Verifikasi Docker

```bash
docker --version
docker compose version
docker buildx version
docker info
docker run --rm hello-world
```

Perintah yang benar adalah:

```text
docker compose
```

Bukan perintah lama:

```text
docker-compose
```

Cara menjelaskan kepada asesor:

> Docker Engine menjalankan container, containerd menangani runtime, Buildx membangun custom image, dan Docker Compose mendefinisikan banyak service dalam satu file YAML.

### 12.8 Jika muncul permission denied

```bash
sudo usermod -aG docker "$USER"
newgrp docker
docker ps
```

Jika masih gagal, reboot VM:

```bash
sudo reboot
```

---

## 13. Instal software khusus VM2

Jalankan hanya pada VM2:

```bash
sudo apt update
sudo apt install -y openssh-server vsftpd
sudo systemctl enable --now ssh
sudo systemctl enable --now vsftpd
```

Periksa:

```bash
systemctl is-active ssh
systemctl is-enabled ssh
systemctl is-active vsftpd
systemctl is-enabled vsftpd
sudo ss -ltnp | grep -E ':22|:21'
```

`openssh-server` akan dikonfigurasi pada bagian SSH. `vsftpd` akan dikonfigurasi pada bagian FTP.

---

## 14. Checklist instalasi software

### VM1

```bash
command -v git
command -v curl
command -v openssl
command -v ssh
command -v docker
docker compose version
systemctl is-active docker
```

### VM2

```bash
command -v docker
command -v sshd
command -v vsftpd
docker compose version
systemctl is-active docker
systemctl is-active ssh
systemctl is-active vsftpd
```

Simpan bukti:

```bash
mkdir -p ~/bukti-ukk
{
  echo '=== HOSTNAME ==='
  hostnamectl
  echo '=== IP ==='
  ip -br address
  echo '=== DOCKER ==='
  docker --version
  docker compose version
} | tee ~/bukti-ukk/instalasi-dasar.txt
```

### 14.1 Buat snapshot VirtualBox

Setelah jaringan dan Docker berfungsi, matikan VM:

```bash
sudo poweroff
```

Buat snapshot bernama:

```text
BASE-UBUNTU-DOCKER-SIAP
```

Snapshot memudahkan mengulang latihan tanpa menginstal dari nol lagi.

---

# BAGIAN C: VM1 DEPLOYMENT APLIKASI

## 15. Ambil source aplikasi

Masuk ke VM1:

```bash
cd /home/ujikom
git clone https://github.com/syberke/hotel-online.git
cd hotel-online
git switch main
git pull origin main
pwd
```

Hasil `pwd`:

```text
/home/ujikom/hotel-online
```

Periksa file utama:

```bash
ls -la
ls Dockerfile docker-compose.yaml composer.json package.json
```

Saat latihan manual, jangan menjalankan:

```text
./deploy.sh
```

---

## 16. Siapkan file rahasia latihan

### 16.1 Buat APP_KEY

```bash
printf 'base64:%s\n' "$(openssl rand -base64 32)"
```

Salin seluruh hasil.

### 16.2 Buat `.env.ukk`

```bash
nano .env.ukk
```

Isi dan sesuaikan `<IP_VM1>` serta `APP_KEY`:

```env
APP_NAME="Oasis Hotel"
APP_ENV=production
APP_KEY=base64:<HASIL_OPENSSL>
APP_DEBUG=false
APP_URL=http://<IP_VM1>:8080
DOCKER_APP_URL=http://<IP_VM1>:8080

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=file

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=info

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=file
CACHE_PREFIX=oasis_ukk_

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@oasishotel.local"
MAIL_FROM_NAME="Oasis Hotel & Resort"

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

Session dan cache memakai `file` agar aplikasi dapat start sebelum tabel tambahan tersedia. Database aplikasi tetap menggunakan MariaDB melalui konfigurasi Compose.

### 16.3 Buat `database.ukk.env`

```bash
nano database.ukk.env
```

Isi:

```env
MARIADB_DATABASE=oasis_hotel
MARIADB_USER=oasis_hotel
MARIADB_PASSWORD=UKK_Oasis_2026
MARIADB_ROOT_PASSWORD=UKK_Root_2026

DB_DATABASE=oasis_hotel
DB_USERNAME=oasis_hotel
DB_PASSWORD=UKK_Oasis_2026
```

Amankan:

```bash
chmod 600 .env.ukk database.ukk.env
ls -l .env.ukk database.ukk.env
```

Pastikan tidak akan masuk Git:

```bash
git check-ignore -v .env.ukk database.ukk.env
```

Pastikan tidak masuk build context image dengan pola `.dockerignore`:

```bash
grep -E '^\.env\.ukk$|^database\.ukk\.env$|^\.env\.\*$|^\*\.env$' .dockerignore
```

Cara menjelaskan kepada asesor:

> Environment dipisahkan dari Dockerfile dan dikecualikan dari Git serta build context agar password tidak tertanam pada source code atau layer image.

---

## 17. Memahami Dockerfile

Buka:

```bash
nano Dockerfile
```

Isi yang digunakan:

```dockerfile
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
COPY public ./public
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist \
    --optimize-autoloader --no-scripts --no-progress \
    --ignore-platform-req=ext-gd

FROM php:8.3-apache-bookworm
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev libicu-dev libjpeg62-turbo-dev libpng-dev \
        curl libpq-dev libwebp-dev libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath gd intl opcache pdo_mysql pdo_pgsql zip \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
RUN rm -f public/hot && test -f public/build/manifest.json

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/hotel-entrypoint

RUN chmod +x /usr/local/bin/hotel-entrypoint \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
ENTRYPOINT ["hotel-entrypoint"]
CMD ["apache2-foreground"]
```

Fungsi tahap:

1. `frontend` membangun CSS dan JavaScript Vite;
2. `vendor` memasang dependency Composer production;
3. runtime menyediakan PHP 8.3 dan Apache;
4. `pdo_mysql` menghubungkan Laravel ke MariaDB;
5. `EXPOSE 80` mendokumentasikan port internal;
6. entrypoint menyiapkan permission dan cache Laravel.

Ini alasan PHP, Composer, Node.js, npm, dan Apache tidak perlu dipasang pada host Ubuntu.

---

## 18. Konfigurasi Apache Laravel

```bash
mkdir -p docker/apache
nano docker/apache/000-default.conf
```

Isi:

```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Options -Indexes +FollowSymLinks
        Require all granted
    </Directory>

    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    PassEnv APP_NODE_NAME APP_NODE_COLOR
    Header always set X-App-Node "%{APP_NODE_NAME}e"

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

`DocumentRoot` harus menunjuk ke folder `public` Laravel. Header `X-App-Node` dipakai untuk membuktikan container yang menjawab request.

---

## 19. Membuat entrypoint

```bash
nano docker/entrypoint.sh
```

Isi:

```sh
#!/bin/sh
set -eu

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

php artisan package:discover --ansi
php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
```

Beri izin:

```bash
chmod +x docker/entrypoint.sh
```

`exec "$@"` mengganti proses shell dengan Apache sehingga signal stop dan restart Docker diterima dengan benar.

---

## 20. Konfigurasi load balancer VM1

```bash
mkdir -p docker/nginx
nano docker/nginx/load-balancer-ukk.conf
```

Isi:

```nginx
upstream hotel_web_cluster {
    least_conn;
    server web1:80;
    server web2:80;
    server web3:80;
}

server {
    listen 80;
    server_name _;

    add_header X-Load-Balancer "oasis-nginx-ukk" always;

    location = /lb-health {
        access_log off;
        default_type text/plain;
        return 200 "load balancer healthy\n";
    }

    location / {
        proxy_pass http://hotel_web_cluster;
        proxy_http_version 1.1;
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Penjelasan:

- `upstream` adalah kumpulan backend;
- Docker DNS menerjemahkan `web1`, `web2`, dan `web3`;
- `least_conn` memilih node dengan koneksi aktif paling sedikit;
- user hanya mengakses load balancer.

---

## 21. Membuat Docker Compose UKK

```bash
nano docker-compose.ukk.yaml
```

Isi:

```yaml
x-web: &web
  build:
    context: .
    dockerfile: Dockerfile
  image: oasis-hotel-ukk:latest
  restart: unless-stopped
  env_file:
    - .env.ukk
    - database.ukk.env
  environment: &web-environment
    APP_ENV: production
    APP_DEBUG: "false"
    APP_URL: http://<IP_VM1>:8080
    DB_CONNECTION: mysql
    DB_HOST: database
    DB_PORT: 3306
  expose:
    - "80"
  networks:
    - network-ujikom
  depends_on:
    database:
      condition: service_healthy
  volumes:
    - public-storage-ujikom:/var/www/html/storage/app/public
  healthcheck:
    test: ["CMD", "curl", "-fsS", "http://localhost/up"]
    interval: 10s
    timeout: 3s
    retries: 5
    start_period: 30s

services:
  web1:
    <<: *web
    environment:
      <<: *web-environment
      APP_NODE_NAME: Web 1
      APP_NODE_COLOR: "#0f766e"

  web2:
    <<: *web
    environment:
      <<: *web-environment
      APP_NODE_NAME: Web 2
      APP_NODE_COLOR: "#b45309"

  web3:
    <<: *web
    environment:
      <<: *web-environment
      APP_NODE_NAME: Web 3
      APP_NODE_COLOR: "#7e22ce"

  database:
    image: mariadb:11.4
    restart: unless-stopped
    env_file:
      - database.ukk.env
    ports:
      - "3306:3306"
    volumes:
      - volume-ujikom:/var/lib/mysql
    networks:
      - network-ujikom
    healthcheck:
      test: ["CMD-SHELL", "healthcheck.sh --connect --innodb_initialized"]
      interval: 5s
      timeout: 3s
      retries: 10

  loadbalancer:
    image: nginx:1.27-alpine
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./docker/nginx/load-balancer-ukk.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      web1:
        condition: service_healthy
      web2:
        condition: service_healthy
      web3:
        condition: service_healthy
    networks:
      - network-ujikom

networks:
  network-ujikom:
    name: network-ujikom
    driver: bridge

volumes:
  volume-ujikom:
    name: volume-ujikom
  public-storage-ujikom:
    name: public-storage-ujikom
```

Ganti `<IP_VM1>`.

Penjelasan penting:

- `build` membuat custom image;
- `web1`, `web2`, dan `web3` adalah horizontal scaling tiga replika;
- `expose: 80` tidak mempublikasikan web ke host;
- `8080:80` mempublikasikan load balancer;
- `3306:3306` memenuhi ketentuan UKK;
- `volume-ujikom` menyimpan data MariaDB;
- `network-ujikom` menyediakan komunikasi dan DNS antar-container;
- `unless-stopped` menyalakan kembali container setelah reboot;
- healthcheck menahan dependency sampai service siap.

> Pada production umum, database sebaiknya tidak dipublikasikan. Port 3306 dibuka di sini karena tercantum pada ketentuan UKK.

---

## 22. Validasi, build, dan jalankan VM1

Validasi YAML:

```bash
docker compose -f docker-compose.ukk.yaml config
```

Build custom image:

```bash
docker compose -f docker-compose.ukk.yaml build --no-cache
```

Jalankan:

```bash
docker compose -f docker-compose.ukk.yaml up -d
```

Periksa:

```bash
docker compose -f docker-compose.ukk.yaml ps
docker ps
docker image ls
docker network ls
docker volume ls
```

Harus ada:

```text
web1
web2
web3
database
loadbalancer
```

Jika nama container memiliki prefix folder, itu normal. Contoh:

```text
hotel-online-web1-1
```

---

## 23. Jalankan migrasi Laravel

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan migrate --force
```

Jika membutuhkan data awal:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan db:seed --force
```

Periksa:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan migrate:status
```

Bersihkan cache jika environment berubah:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan optimize:clear

docker compose -f docker-compose.ukk.yaml restart web1 web2 web3
```

---

## 24. Pengujian VM1

### 24.1 Health check

```bash
curl http://127.0.0.1:8080/lb-health
curl -I http://127.0.0.1:8080
```

### 24.2 Browser host

```text
http://<IP_VM1>:8080
```

### 24.3 Bukti tiga web container

```bash
docker compose -f docker-compose.ukk.yaml ps
```

### 24.4 Bukti load balancing

```bash
for i in 1 2 3 4 5 6 7 8 9; do
  curl -sI http://127.0.0.1:8080 | grep -i X-App-Node
done
```

Hasil harus menampilkan node berbeda:

```text
X-App-Node: Web 1
X-App-Node: Web 2
X-App-Node: Web 3
```

Cara menjelaskan:

> Ketiga container memakai image yang sama. Perbedaannya diberikan melalui environment. Nginx membagi request dan header menunjukkan backend yang merespons.

### 24.5 Bukti network

```bash
docker network inspect network-ujikom
```

### 24.6 Bukti volume

```bash
docker volume inspect volume-ujikom
```

### 24.7 Bukti port

```bash
sudo ss -ltnp | grep -E ':8080|:3306'
docker compose -f docker-compose.ukk.yaml ps
```

Web node hanya menunjukkan `80/tcp`. Load balancer menunjukkan `0.0.0.0:8080->80/tcp`.

### 24.8 Bukti aplikasi terhubung database

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan migrate:status
```

Masuk MariaDB:

```bash
docker compose -f docker-compose.ukk.yaml exec database \
  mariadb -u oasis_hotel -p oasis_hotel
```

Lalu:

```sql
SHOW TABLES;
SELECT DATABASE();
EXIT;
```

Laravel memakai hostname `database`, bukan `localhost`, karena setiap container memiliki localhost sendiri.

---

## 25. Membuktikan persistensi volume

Lihat tabel:

```bash
docker compose -f docker-compose.ukk.yaml exec database \
  mariadb -u oasis_hotel -pUKK_Oasis_2026 oasis_hotel \
  -e "SHOW TABLES;"
```

Buat ulang container tanpa menghapus volume:

```bash
docker compose -f docker-compose.ukk.yaml down
docker compose -f docker-compose.ukk.yaml up -d
```

Periksa tabel lagi. Data tetap ada.

Jangan gunakan ini jika ingin mempertahankan data:

```text
docker compose down -v
```

---

## 26. Membuktikan restart policy

```bash
docker compose -f docker-compose.ukk.yaml ps
sudo reboot
```

Setelah login:

```bash
docker ps
curl -I http://127.0.0.1:8080
```

Container kembali aktif karena `restart: unless-stopped` dan Docker aktif saat boot.

---

# BAGIAN D: VM2 MANAGEMENT

## 27. Konfigurasi OpenSSH Server

Pada VM2:

```bash
sudo systemctl enable --now ssh
sudo systemctl status ssh --no-pager
sudo ss -ltnp | grep ':22'
```

Uji dari VM1 menggunakan password:

```bash
ssh ujikom@<IP_VM2>
```

Ketik:

```bash
exit
```

---

## 28. SSH password-less dari VM1 ke VM2

Pada VM1:

```bash
ssh-keygen -t ed25519 -C "ukk-vm1-to-vm2"
```

Tekan Enter untuk lokasi default. Untuk demonstrasi password-less, passphrase boleh dikosongkan pada lingkungan laboratorium.

Kirim public key:

```bash
ssh-copy-id ujikom@<IP_VM2>
```

Uji:

```bash
ssh -o PasswordAuthentication=no ujikom@<IP_VM2> hostname
```

Hasil harus menampilkan hostname VM2 tanpa meminta password.

Cara menjelaskan:

> VM1 menyimpan private key. VM2 hanya menerima public key di `authorized_keys`. Server membuktikan kepemilikan private key tanpa mengirim password.

---

## 29. Konfigurasi FTP Server VM2

Cadangkan konfigurasi:

```bash
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.backup
```

Buat user FTP:

```bash
sudo adduser ftpukk
sudo mkdir -p /home/ftpukk/upload
sudo chown -R ftpukk:ftpukk /home/ftpukk/upload
```

Edit:

```bash
sudo nano /etc/vsftpd.conf
```

Pastikan nilai berikut ada. Hapus atau komentari nilai duplikat yang bertentangan.

```ini
listen=YES
listen_ipv6=NO
anonymous_enable=NO
local_enable=YES
write_enable=YES
local_umask=022
chroot_local_user=YES
allow_writeable_chroot=YES
pasv_enable=YES
pasv_min_port=30000
pasv_max_port=30010
use_localtime=YES
xferlog_enable=YES
```

Restart:

```bash
sudo systemctl restart vsftpd
sudo systemctl enable vsftpd
sudo systemctl status vsftpd --no-pager
sudo ss -ltnp | grep ':21'
```

Uji dari host menggunakan FileZilla atau WinSCP:

```text
Protocol : FTP
Host     : <IP_VM2>
Port     : 21
Username : ftpukk
Password : password yang dibuat
```

Upload file ke folder `upload`, lalu periksa:

```bash
ls -lah /home/ftpukk/upload
```

FTP biasa tidak terenkripsi. Gunakan hanya untuk demonstrasi laboratorium. Untuk penggunaan nyata, SFTP lebih aman.

---

## 30. Buat tiga web container VM2

```bash
mkdir -p /home/ujikom/management-stack/{site1,site2,site3,nginx}
cd /home/ujikom/management-stack
```

### site1

```bash
nano site1/index.html
```

```html
<!doctype html>
<html>
<head><title>Management Node 1</title></head>
<body style="font-family:Arial;text-align:center;padding-top:80px">
  <h1>Management Web Node 1</h1>
  <p>Container pertama</p>
</body>
</html>
```

### site2

```bash
nano site2/index.html
```

```html
<!doctype html>
<html>
<head><title>Management Node 2</title></head>
<body style="font-family:Arial;text-align:center;padding-top:80px;background:#fff7ed">
  <h1>Management Web Node 2</h1>
  <p>Container kedua</p>
</body>
</html>
```

### site3

```bash
nano site3/index.html
```

```html
<!doctype html>
<html>
<head><title>Management Node 3</title></head>
<body style="font-family:Arial;text-align:center;padding-top:80px;background:#f5f3ff">
  <h1>Management Web Node 3</h1>
  <p>Container ketiga</p>
</body>
</html>
```

---

## 31. Load balancer VM2

```bash
nano nginx/default.conf
```

Isi:

```nginx
upstream management_cluster {
    server site1:80;
    server site2:80;
    server site3:80;
}

server {
    listen 80;
    server_name _;

    add_header X-Management-LB "vm2-nginx" always;

    location / {
        proxy_pass http://management_cluster;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}
```

Nginx menggunakan round-robin secara default jika tidak ada algoritma lain seperti `least_conn`.

---

## 32. Docker Compose VM2

```bash
nano docker-compose.yaml
```

Isi:

```yaml
services:
  site1:
    image: nginx:1.27-alpine
    restart: unless-stopped
    expose:
      - "80"
    volumes:
      - ./site1:/usr/share/nginx/html:ro
    networks:
      - management-network

  site2:
    image: nginx:1.27-alpine
    restart: unless-stopped
    expose:
      - "80"
    volumes:
      - ./site2:/usr/share/nginx/html:ro
    networks:
      - management-network

  site3:
    image: nginx:1.27-alpine
    restart: unless-stopped
    expose:
      - "80"
    volumes:
      - ./site3:/usr/share/nginx/html:ro
    networks:
      - management-network

  loadbalancer:
    image: nginx:1.27-alpine
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - site1
      - site2
      - site3
    networks:
      - management-network

networks:
  management-network:
    driver: bridge
```

Validasi dan jalankan:

```bash
docker compose config
docker compose up -d
docker compose ps
```

---

## 33. Uji load balancing VM2

Dari VM2:

```bash
for i in 1 2 3 4 5 6; do
  curl -s http://127.0.0.1:8080 | grep 'Management Web Node'
done
```

Hasil bergantian antara Node 1, Node 2, dan Node 3.

Dari host:

```text
http://<IP_VM2>:8080
```

Refresh beberapa kali.

Periksa header:

```bash
curl -sI http://127.0.0.1:8080 | grep -i X-Management-LB
```

---

# BAGIAN E: FIREWALL DAN PENGUJIAN

## 34. Konfigurasi UFW

Jangan aktifkan firewall sebelum mengizinkan port SSH jika VM dikelola melalui SSH.

### VM1

```bash
sudo ufw allow 8080/tcp
sudo ufw allow 3306/tcp
sudo ufw enable
sudo ufw status numbered
```

### VM2

```bash
sudo ufw allow 22/tcp
sudo ufw allow 21/tcp
sudo ufw allow 30000:30010/tcp
sudo ufw allow 8080/tcp
sudo ufw enable
sudo ufw status numbered
```

Catatan:

> Port yang dipublikasikan Docker dapat berinteraksi langsung dengan aturan iptables Docker. Untuk UKK, buktikan port dengan `docker compose ps`, `ss`, dan pengujian dari host, bukan hanya berdasarkan output UFW.

---

## 35. Matriks pengujian akhir

| No | Pengujian | Perintah atau cara | Hasil |
|---|---|---|---|
| 1 | Internet VM1 | `ping -c 4 8.8.8.8` | berhasil |
| 2 | Internet VM2 | `ping -c 4 8.8.8.8` | berhasil |
| 3 | VM1 ke VM2 | `ping -c 4 <IP_VM2>` | berhasil |
| 4 | Host ke VM1 | `ping <IP_VM1>` | berhasil |
| 5 | Host ke VM2 | `ping <IP_VM2>` | berhasil |
| 6 | Docker aktif | `systemctl is-active docker` | active |
| 7 | Compose tersedia | `docker compose version` | tampil versi |
| 8 | VM1 lima service | `docker compose -f docker-compose.ukk.yaml ps` | running/healthy |
| 9 | Aplikasi VM1 | browser `<IP_VM1>:8080` | halaman tampil |
| 10 | LB VM1 | loop `X-App-Node` | node berubah |
| 11 | Database | `php artisan migrate:status` | terkoneksi |
| 12 | Volume | recreate container | data tetap ada |
| 13 | Restart policy | reboot VM1 | container hidup kembali |
| 14 | SSH password-less | SSH dengan password auth disabled | hostname VM2 tampil |
| 15 | FTP | upload dari host | file masuk VM2 |
| 16 | VM2 empat service | `docker compose ps` | running |
| 17 | LB VM2 | loop curl | Node 1, 2, dan 3 bergantian |

---

## 36. Bukti screenshot yang harus disiapkan

1. Settings VirtualBox VM1 dengan NAT dan bridge;
2. Settings VirtualBox VM2 dengan NAT dan bridge;
3. `hostnamectl` VM1;
4. `hostnamectl` VM2;
5. `ip -br address` kedua VM;
6. ping VM1 ke VM2;
7. ping host ke kedua VM;
8. versi Docker dan Compose;
9. `docker compose ps` VM1;
10. `docker network inspect network-ujikom`;
11. `docker volume inspect volume-ujikom`;
12. browser aplikasi VM1;
13. header `X-App-Node` yang berubah;
14. tabel database atau `migrate:status`;
15. SSH VM1 ke VM2 tanpa password;
16. status vsftpd;
17. upload FTP;
18. `docker compose ps` VM2;
19. tiga tampilan Node VM2;
20. container aktif setelah reboot.

Simpan dengan nama terurut:

```text
01-virtualbox-vm1.png
02-virtualbox-vm2.png
03-hostname-vm1.png
...
20-restart-policy.png
```

---

## 37. Pertanyaan asesor dan jawaban singkat

### Apa perbedaan image dan container?

> Image adalah template read-only. Container adalah instance proses yang berjalan dari image.

### Mengapa memakai Dockerfile?

> Dockerfile membuat custom image aplikasi secara konsisten dan dapat dibangun ulang.

### Mengapa host tidak diinstal PHP, Composer, dan Node.js?

> Dependency tersebut tersedia di tahap Dockerfile. Ini membuat host lebih sederhana dan deployment konsisten.

### Mengapa memakai dua adapter?

> NAT untuk internet. Bridge untuk komunikasi host, VM1, dan VM2 pada jaringan UKK.

### Mengapa web menggunakan expose?

> Web hanya boleh diakses load balancer melalui network internal, bukan langsung dari host.

### Apa fungsi network-ujikom?

> Network menghubungkan container dan menyediakan DNS berdasarkan nama service.

### Apa fungsi volume-ujikom?

> Volume menyimpan data MariaDB di luar filesystem container agar tetap ada saat container dibuat ulang.

### Apa fungsi restart policy?

> Docker menjalankan kembali container setelah daemon atau VM restart, kecuali container dihentikan secara manual.

### Bagaimana load balancing dibuktikan?

> Request berulang menghasilkan identitas backend yang berbeda melalui header atau tampilan node.

### Mengapa DB_HOST menggunakan database?

> `database` adalah nama service yang diterjemahkan Docker DNS. `localhost` di web container menunjuk ke web container sendiri.

### Apa arti 8080:80?

> Port 8080 pada host diteruskan ke port 80 pada container.

### Apa beda expose dan ports?

> `expose` hanya mendokumentasikan dan menyediakan port pada jaringan container. `ports` mempublikasikan port ke host.

### Mengapa FTP bukan pilihan aman?

> FTP mengirim kredensial dan data tanpa enkripsi. FTP digunakan karena ketentuan laboratorium, sedangkan penggunaan nyata lebih baik memakai SFTP.

### Bagaimana SSH tanpa password bekerja?

> VM1 menandatangani proses autentikasi dengan private key. VM2 memverifikasi menggunakan public key pada `authorized_keys`.

---

## 38. Troubleshooting

### Docker tidak aktif

```bash
sudo systemctl restart docker
sudo systemctl status docker --no-pager
sudo journalctl -u docker -n 100 --no-pager
```

### Permission denied Docker socket

```bash
sudo usermod -aG docker "$USER"
newgrp docker
```

### Repository Docker tidak ditemukan

```bash
cat /etc/apt/sources.list.d/docker.sources
cat /etc/os-release
dpkg --print-architecture
sudo apt update
```

### Compose YAML error

```bash
docker compose -f docker-compose.ukk.yaml config
```

Periksa indentasi spasi, bukan tab.

### Build gagal

```bash
docker compose -f docker-compose.ukk.yaml build --no-cache --progress=plain
```

### Container restart terus

```bash
docker compose -f docker-compose.ukk.yaml ps
docker compose -f docker-compose.ukk.yaml logs --tail=200 web1
docker compose -f docker-compose.ukk.yaml logs --tail=200 database
```

### Port 8080 dipakai

```bash
sudo ss -ltnp | grep ':8080'
```

Jika Apache atau Nginx host tidak sengaja terinstal:

```bash
sudo systemctl disable --now apache2 2>/dev/null || true
sudo systemctl disable --now nginx 2>/dev/null || true
```

### Port 3306 dipakai

```bash
sudo ss -ltnp | grep ':3306'
sudo systemctl disable --now mariadb 2>/dev/null || true
sudo systemctl disable --now mysql 2>/dev/null || true
```

### Aplikasi 500

```bash
docker compose -f docker-compose.ukk.yaml logs --tail=200 web1
docker compose -f docker-compose.ukk.yaml exec web1 \
  tail -n 100 storage/logs/laravel.log
```

### Database belum siap

```bash
docker compose -f docker-compose.ukk.yaml ps
docker compose -f docker-compose.ukk.yaml logs database
```

### VM tidak saling ping

```bash
ip -br address
ip route
sudo netplan generate
sudo ufw status
```

Periksa Adapter 2, kartu jaringan bridge, IP `/24`, dan Cable Connected.

### FTP gagal login

```bash
sudo systemctl status vsftpd --no-pager
sudo journalctl -u vsftpd -n 100 --no-pager
sudo grep -v '^#' /etc/vsftpd.conf | sed '/^$/d'
```

### SSH masih meminta password

```bash
ls -la ~/.ssh
ssh -vvv ujikom@<IP_VM2>
```

Pada VM2:

```bash
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

---

## 39. Urutan demonstrasi kepada asesor

1. Tunjukkan dua VM dan dua adapter;
2. tunjukkan hostname dan IP;
3. lakukan ping dua arah;
4. jelaskan paket yang dipasang;
5. tunjukkan versi Docker dan Compose;
6. tunjukkan Dockerfile;
7. jelaskan multi-stage build;
8. tunjukkan Compose VM1;
9. jelaskan web, database, load balancer, network, volume, dan restart policy;
10. jalankan `docker compose ps`;
11. buka aplikasi dari host;
12. buktikan load balancing;
13. buktikan database dan volume;
14. reboot dan buktikan restart policy;
15. SSH dari VM1 ke VM2 tanpa password;
16. buktikan FTP;
17. buktikan tiga web container VM2;
18. buktikan load balancing VM2;
19. tunjukkan screenshot dan dokumentasi.

---

## 40. Checklist akhir sebelum UKK

### Host

- [ ] VirtualBox terinstal;
- [ ] ISO Ubuntu Server tersedia;
- [ ] FileZilla atau WinSCP tersedia;
- [ ] IP nomor absen sudah dicatat.

### VM1

- [ ] NAT aktif;
- [ ] bridge aktif;
- [ ] hostname benar;
- [ ] IP statis benar;
- [ ] internet aktif;
- [ ] ping VM2 berhasil;
- [ ] paket dasar terinstal;
- [ ] Docker aktif saat boot;
- [ ] Compose tersedia;
- [ ] source berada di `/home/ujikom/hotel-online`;
- [ ] `.env.ukk` dibuat;
- [ ] database env dibuat;
- [ ] Dockerfile dipahami;
- [ ] tiga web container berjalan;
- [ ] MariaDB berjalan;
- [ ] Nginx load balancer berjalan;
- [ ] port 8080 dapat diakses;
- [ ] port 3306 terbuka sesuai soal;
- [ ] `network-ujikom` tersedia;
- [ ] `volume-ujikom` tersedia;
- [ ] load balancing terbukti;
- [ ] restart policy terbukti.

### VM2

- [ ] NAT aktif;
- [ ] bridge aktif;
- [ ] hostname benar;
- [ ] IP statis benar;
- [ ] Docker aktif;
- [ ] OpenSSH aktif;
- [ ] SSH password-less berhasil;
- [ ] vsftpd aktif;
- [ ] FTP dari host berhasil;
- [ ] tiga web container berjalan;
- [ ] load balancer berjalan;
- [ ] tampilan tiga node berbeda;
- [ ] restart policy aktif.

### Dokumentasi

- [ ] screenshot lengkap;
- [ ] urutan demonstrasi dilatih;
- [ ] jawaban pertanyaan asesor dipahami;
- [ ] secret tidak masuk Git;
- [ ] secret tidak masuk Docker image;
- [ ] snapshot VM tersedia.

---

## 41. Pola latihan sebelum hari UKK

### Latihan pertama

Ikuti tutorial sambil melihat dokumen.

### Latihan kedua

Ketik konfigurasi tanpa menyalin seluruh blok sekaligus. Setelah setiap tahap, jelaskan fungsi perintah dengan suara keras.

### Latihan ketiga

Mulai dari snapshot Ubuntu dan Docker, kemudian selesaikan VM1 dan VM2 memakai timer.

### Latihan terakhir

Lakukan simulasi presentasi:

1. sebutkan tujuan;
2. tunjukkan topologi;
3. jelaskan instalasi;
4. jalankan pengujian;
5. tunjukkan bukti;
6. jawab pertanyaan tanpa membaca.

Fokus utama bukan menghafal seluruh sintaks. Fokus pada hubungan berikut:

```text
Host -> port 8080 -> load balancer -> tiga web container -> database
```

serta:

```text
VM1 -> SSH key -> VM2
Host -> FTP -> VM2
```
