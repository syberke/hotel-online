#!/bin/bash
set -e

echo "⏳ Menyinkronkan file lingkungan .env..."
if [ ! -f .env ]; then
    cp .env.example .env
    # Mengubah konfigurasi database .env agar mengarah ke container Docker
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' .env
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env
    sed -i 's/DB_PORT=3306/DB_PORT=5432/g' .env
    sed -i 's/DB_DATABASE=laravel/DB_DATABASE=oasis_db/g' .env
    sed -i 's/DB_USERNAME=root/DB_USERNAME=oasis_admin/g' .env
    sed -i 's/DB_PASSWORD=/DB_PASSWORD=SecretPassword123/g' .env
    sed -i 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/localhost:8085/g' .env
fi

echo "🧱 Membangun arsitektur kontainer Docker..."
docker-compose down
docker-compose up -d --build

echo "📦 Memasang dependency Laravel..."
docker-compose exec -T app composer install --no-dev --optimize-autoloader
docker-compose exec -T app php artisan key:generate

echo "🔒 Mengatur hak akses folder..."
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

echo "🗄️ Menjalankan migrasi database & seeder..."
docker-compose exec -T app php artisan migrate:fresh --seed

echo "⚡ Mengoptimalkan cache aplikasi..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

echo "🚀 SELESAI! Oasis Hotel berjalan lancar di port 8085."