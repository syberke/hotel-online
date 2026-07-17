# Tutorial Manual UKK Instalasi Komputasi Awan

Panduan ini dibuat untuk latihan UKK klaster **Instalasi Komputasi Awan** menggunakan aplikasi **Oasis Hotel Online**.

Seluruh langkah di dokumen ini dilakukan secara manual. Jangan menjalankan `deploy.sh` saat latihan utama. Tujuannya agar setiap konfigurasi dapat dipahami, diketik ulang, diuji, dan dijelaskan kepada asesor.

## 1. Target akhir UKK

Lingkungan yang akan dibangun terdiri dari dua mesin virtual Ubuntu Server.

### VM 1: deployment

- hostname: `namasiswa_deployment`;
- dua adapter jaringan, yaitu NAT dan Bridged Adapter;
- aplikasi berada di `/home/ujikom/hotel-online`;
- custom image dibangun dari `Dockerfile`;
- tiga container web Laravel, yaitu `web1`, `web2`, dan `web3`;
- satu container MariaDB;
- satu container Nginx sebagai load balancer;
- load balancer diakses melalui port `8080`;
- container web hanya memakai `expose`, bukan `ports`;
- database membuka port `3306:3306` sesuai ketentuan UKK;
- database memakai named volume `volume-ujikom`;
- semua container memakai network `network-ujikom`;
- semua service memakai restart policy;
- setiap web node memiliki identitas berbeda untuk membuktikan load balancing.

### VM 2: management

- hostname: `namasiswa_management`;
- dua adapter jaringan, yaitu NAT dan Bridged Adapter;
- OpenSSH Server;
- SSH dari VM1 menuju VM2 tanpa mengetik password;
- FTP Server yang dapat diakses dari komputer host;
- tiga container Nginx dengan tampilan berbeda;
- satu container Nginx sebagai load balancer;
- load balancer VM2 dapat membagi request ke tiga container web.

## 2. Pemetaan ketentuan UKK

| Ketentuan | Implementasi dalam tutorial |
|---|---|
| Dua VM Ubuntu | VM1 deployment dan VM2 management |
| NAT dan bridge | Adapter 1 NAT, Adapter 2 Bridged Adapter |
| VM saling terhubung | Ping VM1 ke VM2 dan sebaliknya |
| Custom image | Image Laravel dibangun dari `Dockerfile` |
| Tiga web container | `web1`, `web2`, dan `web3` |
| Database | MariaDB 11.4 |
| Volume | `volume-ujikom` |
| Network | `network-ujikom` |
| Load balancer | Nginx pada port 8080 |
| Perbedaan setiap container | `APP_NODE_NAME` dan `APP_NODE_COLOR` |
| SSH tanpa password | SSH key ED25519 dari VM1 ke VM2 |
| FTP | vsftpd pada VM2 |
| Pengujian | `ping`, `curl`, browser, Docker CLI, dan FTP client |

## 3. Lembar variabel sebelum mulai

Isi tabel berikut sebelum praktik. Gunakan IP sesuai nomor absen pada lembar UKK.

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

Contoh nomor absen 1 pada tabel UKK:

```text
IP host : 172.20.3.2
IP VM1  : 172.20.3.3
IP VM2  : 172.20.3.4
```

Jangan memakai contoh tersebut apabila nomor absen berbeda.

## 4. Topologi

```text
Komputer Host
     |
     | jaringan bridge 172.20.3.0/24
     |
     +-------------------------------+
     |                               |
     v                               v
VM1 deployment                  VM2 management
<IP_VM1>                        <IP_VM2>
     |                               |
     | :8080                         | :8080
     v                               v
Nginx Load Balancer             Nginx Load Balancer
  |       |       |               |       |       |
 web1    web2    web3           site1   site2   site3
  |       |       |
  +-------+-------+
          |
       MariaDB
```

Fungsi setiap jaringan:

- NAT dipakai agar VM dapat mengakses internet untuk instalasi package dan pull image;
- Bridged Adapter dipakai agar host, VM1, dan VM2 berada pada jaringan yang sama;
- Docker bridge network dipakai untuk komunikasi antar-container menggunakan nama service.

## 5. Membuat dua VM di VirtualBox

Lakukan untuk VM1 dan VM2.

1. Buat VM Ubuntu Server 64-bit.
2. Gunakan RAM minimal 2 GB. Untuk VM1 disarankan 4 GB jika komputer mencukupi.
3. Gunakan disk minimal 25 GB.
4. Buka **Settings > Network**.
5. Adapter 1:
   - Enable Network Adapter;
   - Attached to: `NAT`;
   - Cable Connected aktif.
6. Adapter 2:
   - Enable Network Adapter;
   - Attached to: `Bridged Adapter`;
   - pilih kartu jaringan host yang digunakan;
   - Cable Connected aktif.
7. Instal Ubuntu Server.
8. Buat user bernama `ujikom` agar perintah dalam tutorial konsisten.

## 6. Mengatur hostname

### VM1

```bash
sudo hostnamectl set-hostname namasiswa_deployment
hostnamectl
```

### VM2

```bash
sudo hostnamectl set-hostname namasiswa_management
hostnamectl
```

Jika `hostnamectl` mengganti karakter underscore menjadi tanda hubung, gunakan nama yang diterima sistem dan jelaskan kepada asesor bahwa static hostname Linux mengikuti format hostname yang valid.

Tambahkan pemetaan kedua VM pada `/etc/hosts` di VM1 dan VM2:

```bash
sudo nano /etc/hosts
```

Tambahkan:

```text
<IP_VM1> namasiswa_deployment
<IP_VM2> namasiswa_management
```

## 7. Mengidentifikasi interface jaringan

Jalankan pada masing-masing VM:

```bash
ip -br link
ip -br address
```

Umumnya:

```text
enp0s3 = NAT
enp0s8 = Bridged Adapter
```

Nama interface dapat berbeda. Jangan menyalin nama interface tanpa mengeceknya.

## 8. Mengatur IP statis dengan Netplan

Cadangkan konfigurasi lama:

```bash
sudo cp -a /etc/netplan /etc/netplan.backup
ls -la /etc/netplan
```

Buat file konfigurasi:

```bash
sudo nano /etc/netplan/01-ukk.yaml
```

### Konfigurasi VM1

Ganti nama interface dan IP sesuai hasil pemeriksaan.

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

### Konfigurasi VM2

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

Periksa indentasi YAML, lalu terapkan:

```bash
sudo netplan generate
sudo netplan try
sudo netplan apply
ip -br address
ip route
```

Metric 100 pada NAT membuat jalur internet NAT lebih diprioritaskan. Metric 200 pada bridge tetap mencatat gateway UKK tanpa mengalahkan jalur NAT.

## 9. Menguji jaringan

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

### Dari komputer host

PowerShell:

```powershell
ping <IP_VM1>
ping <IP_VM2>
```

Jika VM1 dan VM2 belum saling ping:

- pastikan Adapter 2 aktif dan mode bridge;
- pastikan memilih kartu jaringan host yang benar;
- periksa IP dan prefix `/24`;
- periksa apakah kedua VM berada pada network `172.20.3.0/24`;
- periksa firewall Ubuntu;
- periksa kabel virtual pada VirtualBox.

## 10. Memperbarui Ubuntu

Jalankan pada VM1 dan VM2:

```bash
sudo apt update
sudo apt upgrade -y
sudo apt install -y ca-certificates curl git openssl nano
```

## 11. Instalasi Docker Engine dan Compose secara manual

Lakukan pada VM1 dan VM2.

Tambahkan key resmi Docker:

```bash
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc
```

Tambahkan repository Docker:

```bash
sudo nano /etc/apt/sources.list.d/docker.sources
```

Isi:

```text
Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: noble
Components: stable
Architectures: amd64
Signed-By: /etc/apt/keyrings/docker.asc
```

Jika Ubuntu bukan 24.04 Noble atau arsitektur bukan amd64, sesuaikan nilai `Suites` dan `Architectures` berdasarkan:

```bash
. /etc/os-release
echo "$VERSION_CODENAME"
dpkg --print-architecture
```

Instal Docker:

```bash
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io \
  docker-buildx-plugin docker-compose-plugin
```

Aktifkan service:

```bash
sudo systemctl enable --now docker
sudo systemctl status docker --no-pager
```

Tambahkan user ke grup Docker:

```bash
sudo usermod -aG docker "$USER"
newgrp docker
```

Verifikasi:

```bash
docker --version
docker compose version
docker run --rm hello-world
```

Penjelasan untuk asesor:

> Docker Engine menjalankan container. Docker Compose mendefinisikan beberapa service dalam satu file YAML. Buildx dipakai saat membangun custom image dari Dockerfile.

## 12. Menyiapkan aplikasi pada VM1

Masuk ke VM1:

```bash
cd /home/ujikom
git clone https://github.com/syberke/hotel-online.git
cd hotel-online
git switch main
git pull origin main
pwd
```

Hasil `pwd` harus:

```text
/home/ujikom/hotel-online
```

Untuk latihan manual, jangan menjalankan:

```text
./deploy.sh
```

## 13. Membuat environment aplikasi secara manual

Buat APP_KEY:

```bash
openssl rand -base64 32
```

Salin hasilnya setelah awalan `base64:`.

Buat file:

```bash
nano .env.ukk
```

Isi dan ganti `<IP_VM1>` serta `APP_KEY`:

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

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
CACHE_STORE=database
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

Buat credential database:

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

Amankan file:

```bash
chmod 600 .env.ukk database.ukk.env
ls -l .env.ukk database.ukk.env
```

Jangan commit kedua file tersebut.

```bash
git status --short
```

Penjelasan untuk asesor:

> Environment dipisahkan dari Dockerfile supaya password dan konfigurasi tidak tertanam di image. Compose akan memasukkan environment ketika container dijalankan.

## 14. Memahami dan mengetik Dockerfile

Buka:

```bash
nano Dockerfile
```

Untuk latihan, ketik ulang isi berikut:

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

Fungsi tahap Dockerfile:

1. `frontend` membangun CSS dan JavaScript Vite;
2. `vendor` menginstal dependency Composer untuk production;
3. runtime menjalankan Laravel menggunakan PHP dan Apache;
4. `pdo_mysql` membuat Laravel dapat terhubung ke MariaDB;
5. `EXPOSE 80` mendokumentasikan port internal container;
6. entrypoint menyiapkan permission dan cache Laravel saat container dimulai.

## 15. Konfigurasi Apache manual

Buka:

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

Header `X-App-Node` dipakai untuk melihat container mana yang menjawab request.

## 16. Membuat entrypoint manual

Buka:

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

Beri izin eksekusi:

```bash
chmod +x docker/entrypoint.sh
```

## 17. Membuat konfigurasi load balancer VM1

Buat file:

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

- `upstream` berisi tujuan load balancing;
- nama `web1`, `web2`, dan `web3` ditemukan melalui DNS internal Docker;
- `least_conn` memilih node dengan koneksi aktif paling sedikit;
- user hanya mengakses load balancer, bukan web node langsung.

## 18. Membuat Docker Compose UKK secara manual

Buat file terpisah agar konfigurasi production repo tidak rusak:

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

### Penjelasan penting Compose

- `build` membangun custom image dari Dockerfile;
- tiga service web merupakan tiga replika horizontal dengan image yang sama;
- `expose: 80` membuat web hanya tersedia di network Docker;
- `ports: 8080:80` mempublikasikan load balancer ke host;
- `ports: 3306:3306` memenuhi ketentuan UKK untuk database;
- `volume-ujikom` menyimpan data MariaDB secara persisten;
- `network-ujikom` memungkinkan komunikasi berdasarkan nama service;
- `restart: unless-stopped` menyalakan kembali container setelah reboot;
- healthcheck memastikan service benar-benar siap.

Catatan keamanan:

> Pada production umum, database sebaiknya tidak dipublikasikan ke host. Dalam tutorial ini port 3306 dibuka karena tercantum dalam ketentuan UKK.

## 19. Validasi konfigurasi sebelum menjalankan

```bash
docker compose -f docker-compose.ukk.yaml config
```

Jika tidak ada error YAML, build image:

```bash
docker compose -f docker-compose.ukk.yaml build
```

Periksa image:

```bash
docker image ls | grep oasis-hotel-ukk
```

## 20. Menjalankan stack VM1

```bash
docker compose -f docker-compose.ukk.yaml up -d
```

Periksa:

```bash
docker compose -f docker-compose.ukk.yaml ps
docker ps
```

Harus terdapat:

```text
web1
web2
web3
database
loadbalancer
```

Jalankan migration dari satu web node:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan migrate --force
```

Jika proyek memerlukan data awal:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan db:seed --force
```

Bersihkan dan buat cache:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan optimize:clear

docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan optimize
```

## 21. Menguji aplikasi VM1

Dari VM1:

```bash
curl -I http://127.0.0.1:8080
curl http://127.0.0.1:8080/lb-health
```

Dari komputer host, buka:

```text
http://<IP_VM1>:8080
```

Jika tidak dapat diakses:

```bash
sudo ss -ltnp | grep ':8080'
docker compose -f docker-compose.ukk.yaml logs loadbalancer
docker compose -f docker-compose.ukk.yaml logs web1
docker compose -f docker-compose.ukk.yaml logs database
```

## 22. Membuktikan load balancing VM1

Jalankan beberapa request:

```bash
for i in 1 2 3 4 5 6 7 8 9; do
  curl -sI http://127.0.0.1:8080 | grep -i X-App-Node
done
```

Hasil harus menunjukkan respons dari node yang berbeda:

```text
X-App-Node: Web 1
X-App-Node: Web 2
X-App-Node: Web 3
```

Pada browser, badge node juga akan memiliki nama dan warna berbeda.

Penjelasan untuk asesor:

> Ketiga container dibangun dari image yang sama. Perbedaannya hanya environment `APP_NODE_NAME` dan `APP_NODE_COLOR`. Nginx membagi request ke tiga node. Perubahan header membuktikan bahwa request tidak selalu dijawab oleh container yang sama.

## 23. Membuktikan network, volume, dan port

### Network

```bash
docker network ls | grep network-ujikom
docker network inspect network-ujikom
```

### Volume

```bash
docker volume ls | grep volume-ujikom
docker volume inspect volume-ujikom
```

### Port

```bash
sudo ss -ltnp | grep -E ':8080|:3306'
```

### Web node tidak dipublikasikan ke host

```bash
docker compose -f docker-compose.ukk.yaml ps
```

Web node hanya menampilkan port internal `80/tcp`, sedangkan load balancer menampilkan `0.0.0.0:8080->80/tcp`.

## 24. Membuktikan database terhubung

```bash
docker compose -f docker-compose.ukk.yaml exec web1 \
  php artisan migrate:status
```

Masuk ke MariaDB:

```bash
docker compose -f docker-compose.ukk.yaml exec database \
  mariadb -u oasis_hotel -p oasis_hotel
```

Masukkan password dari `database.ukk.env`, lalu:

```sql
SHOW TABLES;
SELECT DATABASE();
EXIT;
```

Penjelasan:

> Laravel memakai hostname `database`, bukan localhost. Di dalam container, localhost berarti container itu sendiri. Docker DNS menerjemahkan nama service `database` ke IP container MariaDB.

## 25. Membuktikan persistensi volume

Lihat daftar tabel terlebih dahulu:

```bash
docker compose -f docker-compose.ukk.yaml exec database \
  mariadb -u oasis_hotel -pUKK_Oasis_2026 oasis_hotel \
  -e "SHOW TABLES;"
```

Hapus dan buat ulang container tanpa menghapus volume:

```bash
docker compose -f docker-compose.ukk.yaml down
docker compose -f docker-compose.ukk.yaml up -d
```

Periksa tabel kembali. Data masih ada karena `docker compose down` tidak menghapus named volume.

Jangan memakai opsi berikut saat ingin mempertahankan data:

```text
docker compose down -v
```

## 26. Membuktikan restart policy

```bash
docker compose -f docker-compose.ukk.yaml ps
sudo reboot
```

Setelah VM hidup kembali:

```bash
docker ps
curl -I http://127.0.0.1:8080
```

Container harus kembali berjalan karena memakai `restart: unless-stopped`.

# BAGIAN VM2: MANAGEMENT

## 27. Instalasi dan aktivasi OpenSSH Server di VM2

Pada VM2:

```bash
sudo apt update
sudo apt install -y openssh-server
sudo systemctl enable --now ssh
sudo systemctl status ssh --no-pager
sudo ss -ltnp | grep ':22'
```

Uji dari VM1 menggunakan password terlebih dahulu:

```bash
ssh ujikom@<IP_VM2>
```

Ketik `exit` untuk kembali ke VM1.

## 28. Membuat SSH password-less dari VM1 ke VM2

Pada VM1:

```bash
ssh-keygen -t ed25519 -C "ukk-vm1-to-vm2"
```

Tekan Enter untuk lokasi default. Untuk demonstrasi password-less penuh, kosongkan passphrase saat latihan UKK.

Kirim public key ke VM2:

```bash
ssh-copy-id ujikom@<IP_VM2>
```

Uji tanpa password:

```bash
ssh -o PasswordAuthentication=no ujikom@<IP_VM2> hostname
```

Hasil harus menampilkan hostname VM2 tanpa meminta password.

Penjelasan untuk asesor:

> VM1 menyimpan private key. VM2 hanya menerima public key pada `~/.ssh/authorized_keys`. Server memverifikasi kepemilikan private key tanpa mengirim password melalui jaringan.

## 29. Konfigurasi FTP Server pada VM2

Instal vsftpd:

```bash
sudo apt update
sudo apt install -y vsftpd
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.backup
```

Buat user FTP:

```bash
sudo adduser ftpukk
sudo mkdir -p /home/ftpukk/upload
sudo chown -R ftpukk:ftpukk /home/ftpukk/upload
```

Buka konfigurasi:

```bash
sudo nano /etc/vsftpd.conf
```

Pastikan nilai berikut tersedia:

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

Restart dan verifikasi:

```bash
sudo systemctl enable --now vsftpd
sudo systemctl restart vsftpd
sudo systemctl status vsftpd --no-pager
sudo ss -ltnp | grep ':21'
```

Jika UFW aktif:

```bash
sudo ufw allow 21/tcp
sudo ufw allow 30000:30010/tcp
sudo ufw status
```

Uji dari komputer host menggunakan FileZilla atau WinSCP:

```text
Protocol : FTP
Host     : <IP_VM2>
Port     : 21
Username : ftpukk
Password : password yang dibuat
```

Upload file ke folder `upload`, lalu verifikasi pada VM2:

```bash
ls -lah /home/ftpukk/upload
```

FTP biasa tidak terenkripsi. Gunakan hanya untuk kebutuhan laboratorium UKK.

## 30. Membuat stack load balancing sederhana di VM2

Buat struktur folder:

```bash
mkdir -p /home/ujikom/management-stack/{site1,site2,site3,nginx}
cd /home/ujikom/management-stack
```

### Halaman site1

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

### Halaman site2

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

### Halaman site3

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

## 31. Konfigurasi load balancer VM2

```bash
nano nginx/default.conf
```

Isi:

```nginx
upstream management_cluster {
    round_robin;
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

`round_robin` membagikan request secara bergiliran.

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

## 33. Menguji load balancing VM2

Dari VM2:

```bash
for i in 1 2 3 4 5 6; do
  curl -s http://127.0.0.1:8080 | grep 'Management Web Node'
done
```

Hasil harus bergantian:

```text
Management Web Node 1
Management Web Node 2
Management Web Node 3
```

Dari host buka dan refresh beberapa kali:

```text
http://<IP_VM2>:8080
```

## 34. Matriks pengujian akhir

| No | Pengujian | Perintah atau cara | Hasil yang diharapkan |
|---:|---|---|---|
| 1 | Host ke VM1 | `ping <IP_VM1>` | Reply |
| 2 | Host ke VM2 | `ping <IP_VM2>` | Reply |
| 3 | VM1 ke VM2 | `ping -c 4 <IP_VM2>` | 0% packet loss |
| 4 | Internet VM | `ping -c 4 8.8.8.8` | Reply |
| 5 | DNS VM | `ping -c 4 google.com` | Nama ter-resolve |
| 6 | Docker | `docker run --rm hello-world` | Berhasil |
| 7 | VM1 stack | `docker compose -f docker-compose.ukk.yaml ps` | 5 service running |
| 8 | Aplikasi | Browser `http://<IP_VM1>:8080` | Oasis tampil |
| 9 | Load balancing VM1 | Perulangan `curl -I` | Web 1, 2, 3 |
| 10 | Database | `php artisan migrate:status` | Migration tampil |
| 11 | Volume | `docker volume inspect volume-ujikom` | Volume ditemukan |
| 12 | Network | `docker network inspect network-ujikom` | Container tergabung |
| 13 | Restart | Reboot VM1 | Container hidup kembali |
| 14 | SSH key | SSH VM1 ke VM2 | Tidak meminta password |
| 15 | FTP | Upload dari host | File muncul di VM2 |
| 16 | VM2 stack | Browser `http://<IP_VM2>:8080` | Tiga tampilan bergantian |

## 35. Bukti screenshot yang disarankan

Ambil screenshot berikut untuk dokumentasi:

1. konfigurasi Adapter 1 NAT dan Adapter 2 Bridge pada VM1;
2. konfigurasi Adapter 1 NAT dan Adapter 2 Bridge pada VM2;
3. output `hostnamectl` VM1;
4. output `hostnamectl` VM2;
5. output `ip -br address` kedua VM;
6. ping VM1 ke VM2;
7. ping VM2 ke VM1;
8. versi Docker dan Compose;
9. isi Dockerfile;
10. isi Docker Compose VM1;
11. output `docker compose ps` VM1;
12. output `docker network ls`;
13. output `docker volume ls`;
14. browser aplikasi port 8080;
15. hasil header Web 1, Web 2, dan Web 3;
16. SSH VM1 ke VM2 tanpa password;
17. status vsftpd;
18. file hasil upload FTP;
19. output container VM2;
20. tiga tampilan load balancing VM2.

## 36. Pertanyaan yang kemungkinan ditanyakan asesor

### Mengapa memakai dua adapter?

NAT menyediakan internet untuk VM. Bridged Adapter membuat VM dapat berkomunikasi langsung dengan host dan VM lain pada jaringan yang sama.

### Apa perbedaan image dan container?

Image adalah template read-only hasil build. Container adalah instance yang berjalan dari image.

### Mengapa membuat custom image?

Aplikasi Laravel memiliki dependency PHP, Apache, Composer, dan hasil build Vite yang perlu dikemas secara konsisten. Dockerfile mendefinisikan seluruh proses tersebut.

### Apa beda `expose` dan `ports`?

`expose` menyediakan port hanya untuk komunikasi antar-container. `ports` mempublikasikan port container ke host.

### Mengapa web node tidak memakai `ports`?

Agar user tidak dapat melewati load balancer dan mengakses web node secara langsung.

### Mengapa database memakai volume?

Filesystem container bersifat sementara. Named volume menjaga data tetap ada saat container dihapus dan dibuat ulang.

### Mengapa menggunakan network Docker?

Agar service dapat berkomunikasi secara terisolasi dan menemukan service lain berdasarkan nama, misalnya hostname `database`.

### Apa fungsi load balancer?

Load balancer menerima request dari user dan mendistribusikannya ke beberapa web node sehingga beban terbagi dan layanan tidak bergantung pada satu container.

### Bagaimana membuktikan load balancing?

Setiap container memiliki identitas berbeda. Request berulang menunjukkan header atau halaman yang berasal dari node berbeda.

### Apa fungsi restart policy?

Restart policy memastikan container kembali berjalan setelah crash, Docker daemon restart, atau VM reboot.

### Mengapa SSH dapat masuk tanpa password?

VM1 memiliki private key, sedangkan public key disimpan pada VM2. VM2 memverifikasi bahwa VM1 memiliki private key yang sesuai.

### Apa fungsi FTP?

FTP digunakan untuk memindahkan file dari host menuju VM2 melalui jaringan. Dalam UKK ini FTP dipakai sebagai layanan manajemen file.

### Mengapa database membuka port 3306 padahal dapat dibuat internal?

Port 3306 dibuka karena menjadi ketentuan demonstrasi UKK. Pada deployment production, akses database sebaiknya dibatasi hanya pada network internal.

## 37. Troubleshooting cepat

### `docker: permission denied`

```bash
sudo usermod -aG docker "$USER"
newgrp docker
```

### Port 8080 sudah digunakan

```bash
sudo ss -ltnp | grep ':8080'
```

Hentikan service yang memakai port tersebut.

### Port 3306 sudah digunakan

```bash
sudo ss -ltnp | grep ':3306'
sudo systemctl stop mysql mariadb 2>/dev/null || true
```

### `could not find driver`

Pastikan custom image memiliki `pdo_mysql`:

```bash
docker compose -f docker-compose.ukk.yaml exec web1 php -m | grep pdo_mysql
```

### Laravel 500 setelah container hidup

```bash
docker compose -f docker-compose.ukk.yaml logs web1
docker compose -f docker-compose.ukk.yaml exec web1 php artisan migrate --force
```

### `No application encryption key`

Periksa `.env.ukk`:

```bash
grep '^APP_KEY=' .env.ukk
```

### Database belum siap

```bash
docker compose -f docker-compose.ukk.yaml logs database
docker compose -f docker-compose.ukk.yaml ps
```

### Nginx menampilkan 502

```bash
docker compose -f docker-compose.ukk.yaml logs loadbalancer
docker compose -f docker-compose.ukk.yaml ps web1 web2 web3
```

### SSH masih meminta password

Pada VM1:

```bash
ssh -v ujikom@<IP_VM2>
```

Pada VM2:

```bash
ls -ld ~/.ssh
ls -l ~/.ssh/authorized_keys
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

### FTP gagal login

```bash
sudo systemctl status vsftpd
sudo journalctl -u vsftpd -n 50 --no-pager
sudo ss -ltnp | grep ':21'
```

## 38. Urutan demonstrasi yang aman

Gunakan urutan berikut saat praktik:

```text
1. Tunjukkan dua VM dan dua adapter
2. Tunjukkan hostname dan IP
3. Tunjukkan ping VM1 dan VM2
4. Tunjukkan Dockerfile
5. Tunjukkan Compose VM1
6. Jalankan docker compose config
7. Jalankan build dan up
8. Jalankan migration
9. Tunjukkan aplikasi di port 8080
10. Buktikan Web 1, Web 2, dan Web 3
11. Tunjukkan network dan volume
12. Buktikan restart policy
13. SSH VM1 ke VM2 tanpa password
14. Upload file melalui FTP
15. Tunjukkan tiga container dan load balancer VM2
16. Tunjukkan dokumentasi screenshot
```

## 39. Latihan sebelum UKK

Lakukan latihan minimal dua kali.

### Latihan pertama

Ikuti tutorial sambil membaca seluruh penjelasan.

### Latihan kedua

Coba kerjakan hanya dengan melihat judul setiap bagian. Buka isi tutorial hanya ketika lupa.

### Target hafalan

Hafalkan fungsi, bukan seluruh karakter konfigurasi:

- NAT untuk internet;
- bridge untuk komunikasi host dan VM;
- Dockerfile untuk membuat image;
- Compose untuk menjalankan banyak service;
- expose untuk port internal;
- ports untuk akses host;
- volume untuk persistensi;
- network untuk komunikasi service;
- Nginx untuk load balancing;
- restart policy untuk pemulihan otomatis;
- SSH key untuk autentikasi tanpa password;
- FTP untuk transfer file.

## 40. Checklist selesai

- [ ] Dua VM Ubuntu tersedia
- [ ] Masing-masing VM memiliki NAT dan bridge
- [ ] Hostname VM1 benar
- [ ] Hostname VM2 benar
- [ ] IP statis sesuai nomor absen
- [ ] Host dapat ping kedua VM
- [ ] VM1 dan VM2 saling ping
- [ ] Docker Engine dan Compose terpasang pada kedua VM
- [ ] Aplikasi berada di `/home/ujikom/hotel-online`
- [ ] Custom image berhasil dibangun
- [ ] Web1, web2, dan web3 berjalan
- [ ] Database MariaDB berjalan
- [ ] Database memakai `volume-ujikom`
- [ ] Semua service memakai `network-ujikom`
- [ ] Load balancer VM1 memakai port 8080
- [ ] Database memakai port 3306
- [ ] Web node hanya memakai expose
- [ ] Load balancing VM1 terbukti
- [ ] SSH password-less VM1 ke VM2 berhasil
- [ ] FTP VM2 dapat diakses dari host
- [ ] Tiga web container VM2 berjalan
- [ ] Load balancing VM2 terbukti
- [ ] Restart policy terbukti
- [ ] Screenshot dokumentasi lengkap
