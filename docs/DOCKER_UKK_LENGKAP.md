# Tutorial Lengkap Docker UKK Oasis Hotel Online

Panduan ini dimulai dari **repository tanpa file konfigurasi Docker** sampai aplikasi dapat dibuka dari komputer penguji, database terisi, dan load balancing terbukti bekerja.

Semua file Docker dibuat manual di VM saat praktik. Jangan commit `.env.docker`, password, atau private key.

---

## 1. Target sesuai lembar tugas

### VM1 deployment

- Ubuntu Server dengan NAT dan Bridge;
- project di `/home/ujikom/hotel-online`;
- custom image aplikasi;
- service `web` di-scale menjadi 3 container;
- MariaDB sebagai `database`;
- Nginx sebagai `loadbalancer`;
- load balancer `8080:80`;
- web hanya `expose: 80`;
- database `3306:3306`;
- volume `volume-ujikom`;
- network `network-ujikom`;
- restart policy;
- hostname web container bergantian sebagai bukti load balancing.

### VM2 management

- SSH Server passwordless dari VM1;
- FTP Server yang dapat diakses host;
- 3 container Nginx sederhana;
- satu load balancer port 8080;
- hostname container berbeda.

---

# BAGIAN A. VARIABEL DAN JARINGAN

## 2. Contoh kelompok IP pertama

Ganti sesuai tabel nomor absen dari penguji.

| Variabel | Contoh |
|---|---|
| Username | `ujikom` |
| Nama siswa | `budi` |
| IP host | `172.20.3.2` |
| IP VM1 | `172.20.3.3` |
| IP VM2 | `172.20.3.4` |
| Gateway | `172.20.3.1` |
| DNS | `8.8.8.8` |
| Prefix | `/24` |
| NAT contoh | `enp0s3` |
| Bridge contoh | `enp0s8` |

Periksa interface, jangan menebak:

```bash
ip -br address
ip route
```

## 3. Hostname

Ubuntu lebih aman memakai tanda hubung pada static hostname.

VM1:

```bash
sudo hostnamectl set-hostname budi-deployment
```

VM2:

```bash
sudo hostnamectl set-hostname budi-management
```

## 4. Netplan VM1

```bash
sudo nano /etc/netplan/01-ukk.yaml
```

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

## 5. Netplan VM2

Gunakan konfigurasi yang sama, tetapi alamat Bridge menjadi:

```yaml
addresses:
  - 172.20.3.4/24
```

Terapkan pada masing-masing VM:

```bash
sudo chmod 600 /etc/netplan/01-ukk.yaml
sudo netplan try
sudo netplan apply
```

Uji:

```bash
ping -c 4 172.20.3.4
ping -c 4 8.8.8.8
ping -c 4 google.com
```

Dari VM2, ganti tujuan pertama menjadi IP VM1.

---

# BAGIAN B. INSTALASI DOCKER

## 6. Paket dasar

Jalankan pada VM1 dan VM2:

```bash
sudo apt update
sudo apt install -y ca-certificates curl git gnupg openssl nano unzip jq
```

## 7. Docker resmi Ubuntu

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

Logout lalu login lagi:

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

# BAGIAN C. PROJECT VM1

## 8. Clone aplikasi

```bash
cd /home/ujikom
git clone https://github.com/syberke/hotel-online.git
cd hotel-online
git switch main
git pull origin main
```

File berikut akan dibuat manual:

```text
Dockerfile
entrypoint.sh
docker-compose.yml
nginx.conf
.env.docker
.dockerignore
```

Agar tidak ikut ter-commit:

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

# BAGIAN D. FILE DOCKER VM1

## 9. `.dockerignore`

```bash
nano .dockerignore
```

```dockerignore
.git
.env
.env.docker
node_modules
vendor
public/build
public/hot
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
```

`.env.example` tetap ikut masuk image agar dapat disalin oleh entrypoint.

## 10. `Dockerfile`

```bash
nano Dockerfile
```

```dockerfile
FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && apt-get install -y \
    git unzip curl default-mysql-client nodejs npm \
    libonig-dev libzip-dev libicu-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring zip intl gd bcmath \
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

## 11. `entrypoint.sh`

```bash
nano entrypoint.sh
```

```sh
#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

if [ "${DB_CONNECTION:-mysql}" = "mysql" ]; then
  echo "Menunggu MariaDB..."
  until mysqladmin ping \
    -h "${DB_HOST:-database}" \
    -P "${DB_PORT:-3306}" \
    -u "${DB_USERNAME}" \
    -p"${DB_PASSWORD}" \
    --silent; do
      sleep 3
  done
fi

php artisan optimize:clear
php artisan storage:link --force || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

exec "$@"
```

```bash
chmod +x entrypoint.sh
```

## 12. `.env.docker`

Buat APP_KEY:

```bash
printf 'base64:%s\n' "$(openssl rand -base64 32)"
```

```bash
nano .env.docker
```

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

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

RUN_MIGRATIONS=false

MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
MAIL_MAILER=log
```

Wajib:

```env
DB_HOST=database
```

Jangan memakai `localhost`, `127.0.0.1`, atau IP VM sebagai DB host container.

```bash
chmod 600 .env.docker
```

## 13. `nginx.conf`

```bash
nano nginx.conf
```

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

## 14. `docker-compose.yml`

```bash
nano docker-compose.yml
```

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

Checklist:

- `web` tidak memiliki `container_name`;
- `web` tidak memiliki `ports`;
- web hanya `expose: 80`;
- database `3306:3306`;
- load balancer `8080:80`;
- volume `volume-ujikom`;
- network `network-ujikom`;
- restart policy tersedia.

---

# BAGIAN E. BUKTI LOAD BALANCING

## 15. Route `/instance`

```bash
nano routes/web.php
```

Tambahkan sebelum route group autentikasi:

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
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0f172a;font-family:Arial;color:#e2e8f0}
        main{width:min(560px,calc(100% - 40px));padding:36px;border:1px solid #334155;border-radius:24px;background:#111827}
        .ok{display:inline-block;padding:8px 12px;border-radius:999px;background:#064e3b;color:#a7f3d0;font-weight:700}
        .host{margin-top:20px;padding:18px;border-radius:14px;background:#1e293b;font-family:monospace;font-size:22px;color:#93c5fd;word-break:break-all}
        p{line-height:1.7;color:#94a3b8}
      </style>
    </head>
    <body>
      <main>
        <span class="ok">LOAD BALANCER AKTIF</span>
        <h1>Oasis Hotel Online</h1>
        <p>Refresh halaman ini. Hostname akan berganti saat Nginx membagi request.</p>
        <div class="host">Container: '.e($hostname).'</div>
      </main>
    </body>
    </html>';

    return response($html)
        ->header('Content-Type', 'text/html; charset=UTF-8')
        ->header('X-App-Node', $hostname);
})->name('instance');
```

Route ini menjadi tanda visual bahwa load balancer bekerja.

---

# BAGIAN F. BUILD DAN DATABASE

## 16. Validasi Compose

```bash
docker compose --env-file .env.docker config
```

## 17. Build custom image

```bash
docker compose --env-file .env.docker build web
```

## 18. Jalankan database lebih dahulu

```bash
docker compose --env-file .env.docker up -d database
docker compose --env-file .env.docker ps
docker compose --env-file .env.docker logs database
```

Tunggu database `healthy`.

## 19. Migration satu kali

Jangan mengaktifkan migration bersamaan pada tiga replica.

```bash
docker compose --env-file .env.docker run --rm web php artisan migrate --force
```

Periksa:

```bash
docker compose --env-file .env.docker run --rm web php artisan migrate:status
```

## 20. Isi database

Seeder utama:

```bash
docker compose --env-file .env.docker run --rm web php artisan db:seed --force
```

Pastikan menu dan venue:

```bash
docker compose --env-file .env.docker run --rm web \
  php artisan db:seed --class=RestaurantMenuSeeder --force

docker compose --env-file .env.docker run --rm web \
  php artisan db:seed --class=RestaurantVenueSeeder --force
```

## 21. Buat akun Admin

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

```text
Email    : admin@oasis.test
Password : Admin123!
```

## 22. Periksa database langsung

```bash
docker compose --env-file .env.docker exec database sh -lc \
  'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'
```

```sql
SHOW TABLES;
SELECT id, name, email, role FROM users;
SELECT id, room_number, status FROM rooms ORDER BY room_number;
SELECT id, name, price FROM restaurant_menus LIMIT 10;
SELECT id, name, location, is_active FROM restaurant_venues;
EXIT;
```

Data berikutnya dapat diisi melalui halaman Admin:

- Rooms & Inventory;
- Restaurant → Venues;
- Facilities;
- Users Control;
- Contact Inbox.

## 23. Backup dan restore

Backup:

```bash
docker compose --env-file .env.docker exec database sh -lc \
  'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  > oasis_hotel_backup.sql
```

Restore:

```bash
docker compose --env-file .env.docker exec -T database sh -lc \
  'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
  < oasis_hotel_backup.sql
```

---

# BAGIAN G. MENJALANKAN STACK VM1

## 24. Tiga web dan load balancer

```bash
docker compose --env-file .env.docker up -d --build --scale web=3
```

Periksa:

```bash
docker compose --env-file .env.docker ps
docker ps --format 'table {{.Names}}\t{{.Status}}\t{{.Ports}}'
docker network inspect network-ujikom
docker volume inspect volume-ujikom
```

Log:

```bash
docker compose --env-file .env.docker logs
docker compose --env-file .env.docker logs web
docker compose --env-file .env.docker logs database
docker compose --env-file .env.docker logs loadbalancer
```

## 25. Firewall VM1

```bash
sudo ufw allow OpenSSH
sudo ufw allow 8080/tcp
sudo ufw allow 3306/tcp
sudo ufw enable
sudo ufw status
```

## 26. Akses komputer penguji

Browser:

```text
http://172.20.3.3:8080
```

Tanda load balancer:

```text
http://172.20.3.3:8080/instance
```

Refresh beberapa kali.

Curl VM1:

```bash
curl http://localhost:8080/lb-health

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
```

Header:

```bash
for i in {1..10}; do
  curl -sI http://localhost:8080/instance \
    | grep -Ei 'X-App-Node|X-Load-Balancer|X-Upstream-Address'
done
```

---

# BAGIAN H. SSH PASSWORDLESS VM2

## 27. SSH Server VM2

```bash
sudo apt update
sudo apt install -y openssh-server
sudo systemctl enable --now ssh
sudo ufw allow OpenSSH
```

Pada VM1:

```bash
ssh-keygen -t ed25519
ssh-copy-id ujikom@172.20.3.4
ssh ujikom@172.20.3.4
```

Bukti berhasil: login dari VM1 ke VM2 tanpa password akun VM2.

---

# BAGIAN I. FTP VM2

## 28. vsftpd

```bash
sudo apt update
sudo apt install -y vsftpd
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.backup
sudo nano /etc/vsftpd.conf
```

Pastikan:

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

```bash
sudo systemctl enable --now vsftpd
sudo systemctl restart vsftpd
sudo ufw allow 21/tcp
sudo ufw allow 40000:40100/tcp
mkdir -p /home/ujikom/ftp-upload
```

Dari host:

```text
Host     : 172.20.3.4
Port     : 21
Username : ujikom
Password : password Ubuntu VM2
```

Uji dengan FileZilla atau:

```powershell
ftp 172.20.3.4
```

---

# BAGIAN J. LOAD BALANCING VM2

## 29. Folder praktik

```bash
mkdir -p /home/ujikom/vm2-loadbalancer
cd /home/ujikom/vm2-loadbalancer
```

## 30. `docker-compose.yml` VM2

```bash
nano docker-compose.yml
```

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
      printf "<!doctype html><html><head><meta charset=utf-8><title>VM2</title><style>body{min-height:100vh;margin:0;display:grid;place-items:center;background:#0f172a;color:#e2e8f0;font-family:Arial}main{padding:40px;border:1px solid #334155;border-radius:24px;background:#111827;text-align:center}strong{display:block;margin-top:20px;color:#93c5fd;font-size:24px}</style></head><body><main><h1>VM2 Management Web</h1><p>Load balancing aktif</p><strong>Container: %s</strong></main></body></html>" "$$HOST" > /usr/share/nginx/html/index.html;
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

## 31. `nginx.conf` VM2

```bash
nano nginx.conf
```

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

## 32. Jalankan dan uji VM2

```bash
docker compose up -d --scale web=3
sudo ufw allow 8080/tcp
docker compose ps
```

```bash
for i in {1..10}; do
  curl -s http://localhost:8080 | grep -o 'Container: [^<]*'
  sleep 1
done
```

Browser host:

```text
http://172.20.3.4:8080
```

---

# BAGIAN K. CHECKLIST ASESOR

## 33. VM dan jaringan

```bash
hostnamectl
ip -br address
ip route
ping -c 4 <IP_VM_LAIN>
```

## 34. Docker VM1

```bash
docker compose --env-file .env.docker ps
docker images
docker network inspect network-ujikom
docker volume inspect volume-ujikom
```

Tunjukkan:

- 3 web container;
- 1 database;
- 1 load balancer;
- web tidak membuka port host;
- database 3306;
- load balancer 8080;
- restart policy.

## 35. Database

```bash
docker compose --env-file .env.docker run --rm web php artisan migrate:status
```

Tunjukkan tabel dan data awal.

## 36. Load balancing

```bash
for i in {1..10}; do curl -s http://localhost:8080/instance | grep -o 'Container: [^<]*'; done
```

Tunjukkan browser `/instance` dan hostname yang berganti.

## 37. VM2

- SSH passwordless;
- FTP login dan upload;
- tiga web container;
- load balancer port 8080;
- hostname container bergantian.

---

# BAGIAN L. TROUBLESHOOTING

## 38. Aplikasi tidak terbuka

```bash
docker compose --env-file .env.docker ps
docker compose --env-file .env.docker logs loadbalancer
docker compose --env-file .env.docker logs web
curl http://localhost:8080/lb-health
sudo ufw status
```

## 39. Database gagal tersambung

Pastikan:

```env
DB_HOST=database
DB_PORT=3306
```

```bash
docker compose --env-file .env.docker logs database
docker network inspect network-ujikom
```

## 40. CSS tidak tampil

```bash
docker compose --env-file .env.docker build --no-cache web
docker compose --env-file .env.docker up -d --scale web=3
```

## 41. Hostname tidak berganti

```bash
docker compose --env-file .env.docker up -d --scale web=3 web
docker compose --env-file .env.docker restart loadbalancer
```

## 42. Restart tanpa menghapus data

```bash
docker compose --env-file .env.docker down
docker compose --env-file .env.docker up -d --build --scale web=3
```

## 43. Hapus seluruh data latihan

Perintah ini menghapus volume database:

```bash
docker compose --env-file .env.docker down -v
```

---

## 44. Urutan singkat hari ujian

```text
1. Periksa NAT dan Bridge
2. Atur hostname dan Netplan
3. Uji ping
4. Instal Docker
5. Clone project VM1
6. Ketik enam file Docker manual
7. Tambahkan /instance
8. Build image
9. Jalankan database
10. Migration satu kali
11. Seeder dan akun Admin
12. Jalankan 3 web + load balancer
13. Buka dari komputer penguji
14. Buktikan hostname bergantian
15. SSH passwordless VM2
16. FTP VM2
17. 3 web + load balancer VM2
18. Ambil screenshot dan bukti command
```
