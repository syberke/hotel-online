# Tutorial Lengkap Docker UKK Oasis Hotel Online

Dokumen ini adalah panduan praktik dari kondisi **repository belum memiliki konfigurasi Docker** sampai aplikasi Laravel dapat dibuka dari komputer penguji, database terisi, dan load balancing dapat dibuktikan.

Semua file Docker pada tutorial ini dibuat manual di VM saat praktik. File tersebut tidak disimpan sebagai konfigurasi siap pakai di repository utama.

---

## 1. Target akhir sesuai tugas UKK

### VM1: deployment server

- Ubuntu Server;
- Adapter 1 NAT untuk internet;
- Adapter 2 Bridge untuk jaringan UKK;
- aplikasi berada di `/home/ujikom/hotel-online`;
- custom image dari `Dockerfile`;
- service `web` di-scale menjadi 3 container;
- service `database` menggunakan MariaDB;
- service `loadbalancer` menggunakan Nginx;
- load balancer membuka `8080:80`;
- web hanya memakai `expose: 80`;
- database membuka `3306:3306`;
- volume bernama `volume-ujikom`;
- network bernama `network-ujikom`;
- seluruh service memakai `restart: unless-stopped`;
- hostname ketiga container web berbeda dan dapat dilihat melalui `/instance`.

### VM2: management server

- SSH Server;
- akses passwordless dari VM1;
- FTP Server yang dapat diakses komputer host;
- 3 container Nginx sederhana;
- satu load balancer pada port 8080;
- tampilan setiap container berbeda berdasarkan hostname.

---

## 2. Contoh variabel latihan

Contoh ini mengikuti kelompok IP pertama pada tabel UKK. Ganti sesuai nomor absen dan arahan penguji.

| Variabel | Contoh |
|---|---|
| Username Ubuntu | `ujikom` |
| Nama siswa | `budi` |
| Project | `hotel-online` |
| IP host | `172.20.3.2` |
| IP VM1 | `172.20.3.3` |
| IP VM2 | `172.20.3.4` |
| Gateway | `172.20.3.1` |
| DNS | `8.8.8.8` |
| Prefix | `/24` |
| Interface NAT contoh | `enp0s3` |
| Interface Bridge contoh | `enp0s8` |

Jangan menyalin nama interface sebelum memeriksa:

```bash
ip -br address
ip route
```

---

# BAGIAN A. MEMBUAT DAN MENYIAPKAN VM

## 3. Membuat dua VM

Buat dua Ubuntu Server pada VirtualBox.

### VM1

- RAM disarankan 4 GB;
- CPU 2 core;
- disk minimal 25 GB;
- Adapter 1 NAT;
- Adapter 2 Bridged Adapter.

### VM2

- RAM 2 GB;
- CPU 1 sampai 2 core;
- disk minimal 20 GB;
- Adapter 1 NAT;
- Adapter 2 Bridged Adapter.

Gunakan username:

```text
ujikom
```

## 4. Mengatur hostname

Linux lebih aman memakai tanda hubung pada static hostname.

VM1:

```bash
sudo hostnamectl set-hostname budi-deployment
```

VM2:

```bash
sudo hostnamectl set-hostname budi-management
```

Periksa:

```bash
hostnamectl
```

## 5. Netplan VM1

Lihat nama interface:

```bash
ip -br address
ip route
```

Buat file:

```bash
sudo nano /etc/netplan/01-ukk.yaml
```

Contoh VM1:

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
        - 172.20.3.3/24
      routes:
        - to: default
          via: 172.20.3.1
          metric: 200
      nameservers:
        addresses:
          - 8.8.8.8
```

Terapkan:

```bash
sudo chmod 600 /etc/netplan/01-ukk.yaml
sudo netplan try
sudo netplan apply
```

## 6. Netplan VM2

Gunakan struktur yang sama, tetapi IP Bridge menjadi IP VM2:

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
        - 172.20.3.4/24
      routes:
        - to: default
          via: 172.20.3.1
          metric: 200
      nameservers:
        addresses:
          - 8.8.8.8
```

## 7. Menguji jaringan

Dari VM1:

```bash
ping -c 4 172.20.3.4
ping -c 4 8.8.8.8
ping -c 4 google.com
```

Dari VM2:

```bash
ping -c 4 172.20.3.3
ping -c 4 8.8.8.8
ping -c 4 google.com
```

Dari komputer host:

```powershell
ping 172.20.3.3
ping 172.20.3.4
```

---

# BAGIAN B. INSTALASI DOCKER

## 8. Instal paket dasar pada kedua VM

```bash
sudo apt update
sudo apt install -y ca-certificates curl git openssl nano unzip jq iputils-ping
```

## 9. Instal Docker resmi

Jalankan pada VM1 dan VM2:

```bash
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

. /etc/os-release

echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $VERSION_CODENAME stable" \
  | sudo tee /etc/apt/sources.list.d/docker.list >/dev/null

sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker "$USER"
```

Logout lalu login kembali:

```bash
exit
```

Verifikasi:

```bash
docker --version
docker compose version
docker info
```

---

# BAGIAN C. MENYIAPKAN PROJECT DI VM1

## 10. Mengambil source code

```bash
cd /home/ujikom
git clone https://github.com/syberke/hotel-online.git
cd hotel-online
git switch main
git pull origin main
```

Pastikan konfigurasi Docker memang belum tersedia:

```bash
ls -la
```

File Docker berikut akan dibuat manual:

```text
Dockerfile
entrypoint.sh
docker-compose.yml
nginx.conf
.env.docker
.dockerignore
```

Agar file praktik tidak ikut ter-commit, masukkan ke exclude lokal Git:

```bash
cat >> .git/info/exclude <<'EOF'
Dockerfile
entrypoint.sh
docker-compose.yml
nginx.conf
.env.docker
.dockerignore
EOF
```

---

# BAGIAN D. KONFIGURASI DOCKER VM1

## 11. Membuat `.dockerignore`

```bash
nano .dockerignore
```

Isi:

```dockerignore
.git
.env
.env.*
node_modules
vendor
public/build
public/hot
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
```

## 12. Membuat `Dockerfile`

```bash
nano Dockerfile
```

Isi:

```dockerfile
FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && apt-get install -y \
    git unzip curl default-mysql-client nodejs npm \
    libzip-dev libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql zip intl gd bcmath \
    && a2enmod rewrite headers \
    && sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
       /etc/apache2/sites-available/*.conf \
       /etc/apache2/apache2.conf \
       /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data storage bootstrap/cache

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
```

Fungsi utama:

- PHP 8.3 sesuai kebutuhan aplikasi;
- Apache diarahkan ke folder `public`;
- ekstensi MariaDB dipasang melalui `pdo_mysql`;
- Composer dan npm membangun aplikasi pada saat image dibuat;
- `entrypoint.sh` dijalankan setiap container dimulai.

## 13. Membuat `entrypoint.sh`

```bash
nano entrypoint.sh
```

Isi:

```sh
#!/bin/sh
set -e

cd /var/www/html

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

php artisan optimize:clear
php artisan storage:link --force || true

if [ "${DB_CONNECTION:-mysql}" = "mysql" ]; then
  echo "Menunggu database..."
  until mysqladmin ping \
    -h "${DB_HOST:-database}" \
    -P "${DB_PORT:-3306}" \
    -u "${DB_USERNAME}" \
    -p"${DB_PASSWORD}" \
    --silent; do
      sleep 3
  done
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

exec "$@"
```

Berikan permission:

```bash
chmod +x entrypoint.sh
```

## 14. Membuat `.env.docker`

Buat APP_KEY terlebih dahulu:

```bash
printf 'base64:%s\n' "$(openssl rand -base64 32)"
```

Salin hasil lengkapnya.

```bash
nano .env.docker
```

Contoh isi:

```env
APP_NAME="Oasis Hotel"
APP_ENV=production
APP_KEY=base64:GANTI_DENGAN_HASIL_OPENSSL
APP_DEBUG=false
APP_URL=http://172.20.3.3:8080

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=oasis_hotel
DB_USERNAME=oasis_hotel
DB_PASSWORD=UkkHotel123!

MARIADB_DATABASE=oasis_hotel
MARIADB_USER=oasis_hotel
MARIADB_PASSWORD=UkkHotel123!
MARIADB_ROOT_PASSWORD=RootUkk123!

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

RUN_MIGRATIONS=false

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=

MAIL_MAILER=log
```

Yang wajib diperhatikan:

```env
DB_HOST=database
```

Jangan memakai `localhost`, `127.0.0.1`, atau IP VM untuk komunikasi dari Laravel ke MariaDB container.

Amankan file:

```bash
chmod 600 .env.docker
```

## 15. Membuat `nginx.conf`

```bash
nano nginx.conf
```

Isi:

```nginx
resolver 127.0.0.11 valid=5s ipv6=off;

upstream hotel_web {
    zone hotel_web 64k;
    server web:80 resolve;
}

server {
    listen 80;
    server_name _;

    location = /lb-health {
        default_type text/plain;
        return 200 "load balancer aktif\n";
    }

    location / {
        proxy_pass http://hotel_web;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_connect_timeout 10s;
        proxy_read_timeout 60s;

        add_header X-Load-Balancer "nginx-ukk" always;
        add_header X-Upstream-Address $upstream_addr always;
    }
}
```

Nginx memakai nama service `web`, bukan IP container. Docker DNS akan memberikan alamat ketiga replica web.

## 16. Membuat `docker-compose.yml`

```bash
nano docker-compose.yml
```

Isi:

```yaml
services:
  web:
    build: .
    restart: unless-stopped
    env_file:
      - .env.docker
    expose:
      - "80"
    depends_on:
      database:
        condition: service_healthy
    networks:
      - network-ujikom

  database:
    image: mariadb:11.4
    restart: unless-stopped
    env_file:
      - .env.docker
    ports:
      - "3306:3306"
    volumes:
      - volume-ujikom:/var/lib/mysql
    healthcheck:
      test: ["CMD-SHELL", "healthcheck.sh --connect --innodb_initialized"]
      interval: 5s
      timeout: 3s
      retries: 20
    networks:
      - network-ujikom

  loadbalancer:
    image: nginx:1.27-alpine
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - web
    networks:
      - network-ujikom

volumes:
  volume-ujikom:
    name: volume-ujikom

networks:
  network-ujikom:
    name: network-ujikom
    driver: bridge
```

Checklist file ini:

- service web tidak mempunyai `container_name`;
- service web tidak mempunyai `ports`;
- web hanya memakai `expose: 80`;
- database memakai `3306:3306`;
- load balancer memakai `8080:80`;
- volume bernama `volume-ujikom`;
- network bernama `network-ujikom`;
- semua service memakai restart policy.

---

# BAGIAN E. TANDA LOAD BALANCING PADA APLIKASI

## 17. Menambahkan route `/instance`

Buka:

```bash
nano routes/web.php
```

Tambahkan setelah deklarasi route publik dan sebelum group authentication:

```php
Route::get('/instance', function () {
    $hostname = gethostname() ?: 'unknown-container';

    $html = '<!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bukti Load Balancer</title>
        <style>
            body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0f172a;font-family:Arial,sans-serif;color:#e2e8f0}
            .card{width:min(560px,calc(100% - 40px));padding:36px;border:1px solid #334155;border-radius:24px;background:#111827;box-shadow:0 24px 70px rgba(0,0,0,.35)}
            .ok{display:inline-block;padding:8px 12px;border-radius:999px;background:#064e3b;color:#a7f3d0;font-weight:700}
            h1{margin:20px 0 8px;font-size:28px}
            .host{margin-top:20px;padding:18px;border-radius:14px;background:#1e293b;font-family:monospace;font-size:22px;color:#93c5fd;word-break:break-all}
            p{line-height:1.7;color:#94a3b8}
        </style>
    </head>
    <body>
        <main class="card">
            <span class="ok">LOAD BALANCER AKTIF</span>
            <h1>Oasis Hotel Online</h1>
            <p>Refresh halaman ini beberapa kali. Nama container akan berganti ketika Nginx membagi request ke tiga replica web.</p>
            <div class="host">Container: '.e($hostname).'</div>
        </main>
    </body>
    </html>';

    return response($html)
        ->header('Content-Type', 'text/html; charset=UTF-8')
        ->header('X-App-Node', $hostname);
})->name('instance');
```

Periksa syntax:

```bash
php -l routes/web.php
```

Jika PHP belum terpasang di host, pemeriksaan dilakukan setelah image dibangun:

```bash
docker compose run --rm web php -l routes/web.php
```

---

# BAGIAN F. BUILD, DATABASE, MIGRATION, DAN SEED

## 18. Memeriksa konfigurasi Compose

```bash
docker compose --env-file .env.docker config
```

Periksa bahwa tidak ada error YAML.

## 19. Build custom image

```bash
docker compose --env-file .env.docker build web
```

Periksa image:

```bash
docker images | grep hotel
```

## 20. Menjalankan database terlebih dahulu

```bash
docker compose --env-file .env.docker up -d database
```

Periksa:

```bash
docker compose --env-file .env.docker ps
docker compose --env-file .env.docker logs database
```

Tunggu sampai status database `healthy`.

## 21. Menjalankan migration satu kali

Jangan mengaktifkan `RUN_MIGRATIONS=true` pada tiga replica web secara bersamaan.

Jalankan migration dari satu container sementara:

```bash
docker compose --env-file .env.docker run --rm web php artisan migrate --force
```

Lihat status migration:

```bash
docker compose --env-file .env.docker run --rm web php artisan migrate:status
```

## 22. Mengisi database dengan seeder

Jalankan seeder utama:

```bash
docker compose --env-file .env.docker run --rm web php artisan db:seed --force
```

Seeder restoran dan venue dapat dipastikan dengan:

```bash
docker compose --env-file .env.docker run --rm web \
  php artisan db:seed --class=RestaurantMenuSeeder --force

docker compose --env-file .env.docker run --rm web \
  php artisan db:seed --class=RestaurantVenueSeeder --force
```

## 23. Membuat akun Admin UKK

Jalankan satu baris berikut:

```bash
docker compose --env-file .env.docker run --rm web php artisan tinker --execute="
\App\Models\User::updateOrCreate(
    ['email' => 'admin@oasis.test'],
    [
        'name' => 'Admin UKK',
        'password' => \Illuminate\Support\Facades\Hash::make('Admin123!'),
        'role' => 'admin',
        'account_status' => 'active',
        'email_verified_at' => now(),
    ]
);
"
```

Login contoh:

```text
Email    : admin@oasis.test
Password : Admin123!
```

Ganti password setelah demonstrasi.

## 24. Melihat isi database dari terminal

Masuk ke MariaDB container:

```bash
docker compose --env-file .env.docker exec database sh -lc \
  'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'
```

Command SQL pemeriksaan:

```sql
SHOW TABLES;
SELECT id, name, email, role FROM users;
SELECT id, room_number, status FROM rooms ORDER BY room_number;
SELECT id, name, price FROM restaurant_menus ORDER BY id LIMIT 10;
SELECT id, name, location, is_active FROM restaurant_venues;
EXIT;
```

## 25. Mengisi data melalui aplikasi

Setelah aplikasi hidup, login sebagai Admin dan gunakan menu:

- Rooms & Inventory untuk kamar dan tipe kamar;
- Restaurant → Venues untuk tempat makan;
- Facilities untuk fasilitas;
- Users Control untuk akun;
- Contact Inbox untuk pesan;
- Reports untuk pemeriksaan data.

Seeder dipakai untuk data awal. CRUD aplikasi dipakai untuk mengubah data saat demonstrasi.

## 26. Backup database

```bash
docker compose --env-file .env.docker exec database sh -lc \
  'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  > oasis_hotel_backup.sql
```

## 27. Restore database

```bash
docker compose --env-file .env.docker exec -T database sh -lc \
  'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  < oasis_hotel_backup.sql
```

---

# BAGIAN G. MENJALANKAN TIGA WEB DAN LOAD BALANCER

## 28. Menjalankan stack

```bash
docker compose --env-file .env.docker up -d --scale web=3 web loadbalancer
```

Atau jalankan semua service sekaligus:

```bash
docker compose --env-file .env.docker up -d --build --scale web=3
```

## 29. Memeriksa container

```bash
docker compose --env-file .env.docker ps
docker ps --format 'table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}'
```

Hasil harus menunjukkan:

```text
hotel-online-web-1
hotel-online-web-2
hotel-online-web-3
hotel-online-database-1
hotel-online-loadbalancer-1
```

Nama prefix dapat berbeda mengikuti nama folder project.

## 30. Memeriksa network dan volume

```bash
docker network inspect network-ujikom
docker volume inspect volume-ujikom
```

## 31. Memeriksa log

```bash
docker compose --env-file .env.docker logs
docker compose --env-file .env.docker logs web
docker compose --env-file .env.docker logs database
docker compose --env-file .env.docker logs loadbalancer
```

Mode mengikuti log:

```bash
docker compose --env-file .env.docker logs -f loadbalancer web
```

---

# BAGIAN H. AKSES DARI KOMPUTER PENGUJI

## 32. Membuka firewall VM1

```bash
sudo ufw allow OpenSSH
sudo ufw allow 8080/tcp
sudo ufw allow 3306/tcp
sudo ufw enable
sudo ufw status
```

## 33. Pengujian dari VM1

```bash
curl http://localhost:8080/lb-health
curl http://localhost:8080
curl http://localhost:8080/instance
```

## 34. Pengujian dari komputer host

PowerShell:

```powershell
curl http://172.20.3.3:8080
curl http://172.20.3.3:8080/instance
```

Browser:

```text
http://172.20.3.3:8080
```

Bukti load balancing:

```text
http://172.20.3.3:8080/instance
```

Refresh beberapa kali. Kotak harus menampilkan hostname container berbeda.

## 35. Bukti load balancing dengan curl

```bash
for i in {1..10}; do
  curl -s http://localhost:8080/instance \
    | grep -o 'Container: [^<]*'
  sleep 1
done
```

Contoh hasil:

```text
Container: hotel-online-web-1
Container: hotel-online-web-2
Container: hotel-online-web-3
Container: hotel-online-web-1
```

Periksa header:

```bash
for i in {1..10}; do
  curl -sI http://localhost:8080/instance \
    | grep -Ei 'X-App-Node|X-Load-Balancer|X-Upstream-Address'
done
```

Header yang diharapkan:

```text
X-App-Node: hotel-online-web-2
X-Load-Balancer: nginx-ukk
X-Upstream-Address: 172.xx.xx.xx:80
```

---

# BAGIAN I. VM2 SSH PASSWORDLESS

## 36. Instal SSH Server pada VM2

```bash
sudo apt update
sudo apt install -y openssh-server
sudo systemctl enable --now ssh
sudo systemctl status ssh
sudo ufw allow OpenSSH
```

## 37. Membuat SSH key pada VM1

```bash
ssh-keygen -t ed25519
```

Tekan Enter untuk lokasi default.

Kirim public key ke VM2:

```bash
ssh-copy-id ujikom@172.20.3.4
```

Uji:

```bash
ssh ujikom@172.20.3.4
```

Bukti berhasil: VM1 masuk ke VM2 tanpa mengetik password akun VM2.

---

# BAGIAN J. FTP VM2

## 38. Instal vsftpd

Pada VM2:

```bash
sudo apt update
sudo apt install -y vsftpd
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.backup
sudo nano /etc/vsftpd.conf
```

Pastikan pengaturan berikut tersedia:

```conf
listen=YES
listen_ipv6=NO
anonymous_enable=NO
local_enable=YES
write_enable=YES
local_umask=022
chroot_local_user=YES
allow_writeable_chroot=YES
pasv_enable=YES
pasv_min_port=40000
pasv_max_port=40100
```

Restart:

```bash
sudo systemctl enable --now vsftpd
sudo systemctl restart vsftpd
sudo systemctl status vsftpd
```

Firewall:

```bash
sudo ufw allow 21/tcp
sudo ufw allow 40000:40100/tcp
```

Buat folder upload:

```bash
mkdir -p /home/ujikom/ftp-upload
chmod 755 /home/ujikom/ftp-upload
```

Pengujian dari komputer host:

```text
Host     : 172.20.3.4
Port     : 21
Username : ujikom
Password : password Ubuntu VM2
```

Gunakan FileZilla atau:

```powershell
ftp 172.20.3.4
```

Upload satu file sebagai bukti.

---

# BAGIAN K. LOAD BALANCING SEDERHANA VM2

## 39. Membuat folder praktik VM2

```bash
mkdir -p /home/ujikom/vm2-loadbalancer
cd /home/ujikom/vm2-loadbalancer
```

## 40. Membuat `docker-compose.yml` VM2

```bash
nano docker-compose.yml
```

Isi:

```yaml
services:
  web:
    image: nginx:1.27-alpine
    restart: unless-stopped
    expose:
      - "80"
    command: >
      /bin/sh -c '
      HOST=$$(hostname);
      cat > /usr/share/nginx/html/index.html <<EOF
      <!doctype html>
      <html>
      <head>
        <meta charset="utf-8">
        <title>VM2 Load Balancer</title>
        <style>
          body{min-height:100vh;margin:0;display:grid;place-items:center;background:#0f172a;color:#e2e8f0;font-family:Arial}
          main{padding:40px;border:1px solid #334155;border-radius:24px;background:#111827;text-align:center}
          strong{display:block;margin-top:20px;color:#93c5fd;font-size:24px}
        </style>
      </head>
      <body><main><h1>VM2 Management Web</h1><p>Load balancing aktif</p><strong>Container: '$$HOST'</strong></main></body>
      </html>
      EOF
      nginx -g "daemon off;"'
    networks:
      - network-ujikom

  loadbalancer:
    image: nginx:1.27-alpine
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - web
    networks:
      - network-ujikom

networks:
  network-ujikom:
    name: network-ujikom
    driver: bridge
```

## 41. Membuat `nginx.conf` VM2

```bash
nano nginx.conf
```

Isi:

```nginx
resolver 127.0.0.11 valid=5s ipv6=off;

upstream vm2_web {
    zone vm2_web 64k;
    server web:80 resolve;
}

server {
    listen 80;
    server_name _;

    location / {
        proxy_pass http://vm2_web;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        add_header X-Load-Balancer "vm2-nginx" always;
        add_header X-Upstream-Address $upstream_addr always;
    }
}
```

## 42. Menjalankan tiga container VM2

```bash
docker compose up -d --scale web=3
```

Periksa:

```bash
docker compose ps
docker network inspect network-ujikom
```

## 43. Menguji load balancing VM2

Dari VM2:

```bash
for i in {1..10}; do
  curl -s http://localhost:8080 | grep -o 'Container: [^<]*'
  sleep 1
done
```

Dari komputer host:

```text
http://172.20.3.4:8080
```

Refresh beberapa kali untuk melihat hostname container berbeda.

Buka firewall VM2:

```bash
sudo ufw allow 8080/tcp
```

---

# BAGIAN L. CHECKLIST DEMONSTRASI ASESOR

## 44. Virtualisasi dan jaringan

```bash
hostnamectl
ip -br address
ip route
ping -c 4 172.20.3.4
```

Tunjukkan:

- dua VM;
- dua adapter pada setiap VM;
- NAT aktif;
- Bridge aktif;
- VM1 dan VM2 saling ping.

## 45. Docker VM1

```bash
docker compose --env-file .env.docker ps
docker images
docker network inspect network-ujikom
docker volume inspect volume-ujikom
```

Tunjukkan:

- custom image;
- tiga web container;
- satu database;
- satu load balancer;
- web tidak membuka port host;
- database membuka 3306;
- load balancer membuka 8080;
- restart policy aktif.

## 46. Database

```bash
docker compose --env-file .env.docker run --rm web php artisan migrate:status
```

Kemudian masuk MariaDB dan tunjukkan tabel serta data.

## 47. Load balancing VM1

```bash
for i in {1..10}; do curl -s http://localhost:8080/instance | grep -o 'Container: [^<]*'; done
```

Tunjukkan browser `/instance` dan refresh.

## 48. Management VM2

Tunjukkan:

- SSH dari VM1 tanpa password;
- FTP login dan upload dari host;
- tiga container web VM2;
- browser VM2 port 8080;
- hostname container bergantian.

---

# BAGIAN M. TROUBLESHOOTING

## 49. Aplikasi tidak dapat dibuka

```bash
docker compose --env-file .env.docker ps
docker compose --env-file .env.docker logs loadbalancer
docker compose --env-file .env.docker logs web
curl http://localhost:8080/lb-health
sudo ufw status
```

## 50. Database connection refused

Pastikan:

```env
DB_HOST=database
DB_PORT=3306
```

Periksa:

```bash
docker compose --env-file .env.docker ps database
docker compose --env-file .env.docker logs database
docker network inspect network-ujikom
```

## 51. Migration gagal

```bash
docker compose --env-file .env.docker run --rm web php artisan optimize:clear
docker compose --env-file .env.docker run --rm web php artisan migrate:status
docker compose --env-file .env.docker run --rm web php artisan migrate --force
```

## 52. CSS tidak tampil

Build ulang image:

```bash
docker compose --env-file .env.docker build --no-cache web
docker compose --env-file .env.docker up -d --scale web=3
```

## 53. Nginx hanya mengarah ke satu container

Restart load balancer setelah tiga replica web aktif:

```bash
docker compose --env-file .env.docker up -d --scale web=3 web
docker compose --env-file .env.docker restart loadbalancer
```

Lalu uji lagi:

```bash
for i in {1..10}; do curl -s http://localhost:8080/instance | grep -o 'Container: [^<]*'; done
```

## 54. Mengulang praktik dari awal tanpa menghapus database

```bash
docker compose --env-file .env.docker down
docker compose --env-file .env.docker up -d --build --scale web=3
```

## 55. Menghapus seluruh data latihan

Perintah berikut menghapus volume database. Gunakan hanya jika benar-benar ingin mengulang dari nol:

```bash
docker compose --env-file .env.docker down -v
```

Verifikasi volume sudah hilang:

```bash
docker volume ls | grep volume-ujikom || true
```

---

## 56. Urutan singkat hari ujian

```text
1. Periksa dua adapter VM
2. Atur hostname
3. Isi Netplan sesuai tabel IP
4. Uji ping VM1 dan VM2
5. Instal Docker
6. Clone project pada VM1
7. Ketik file Docker manual dari dokumen ini
8. Tambahkan route /instance
9. Build custom image
10. Jalankan database
11. Jalankan migration satu kali
12. Jalankan seeder
13. Buat Admin
14. Jalankan 3 web + load balancer
15. Buka http://<IP_VM1>:8080
16. Buktikan /instance bergantian
17. Konfigurasi SSH passwordless VM2
18. Konfigurasi FTP VM2
19. Jalankan 3 web + load balancer VM2
20. Ambil bukti screenshot dan command
```
