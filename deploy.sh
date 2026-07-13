#!/bin/bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

if docker compose version >/dev/null 2>&1; then
    COMPOSE_BIN=(docker compose)
elif command -v docker-compose >/dev/null 2>&1; then
    COMPOSE_BIN=(docker-compose)
else
    echo "❌ Docker Compose tidak tersedia. Instal Docker + Compose plugin terlebih dahulu." >&2
    exit 1
fi

if ! docker info >/dev/null 2>&1; then
    echo "❌ Docker daemon tidak aktif. Jalankan Docker service terlebih dahulu." >&2
    exit 1
fi

printf '=======================================================================\n'
printf '   OASIS HOTEL & RESORT - WEB CLUSTER DEPLOYMENT                 \n'
printf '=======================================================================\n'

echo "🧱 Menyiapkan 3 node web Apache + load balancer Nginx"
echo "🗄️ Database mengikuti mode yang dikonfigurasi di .env"
echo "🌐 Akses: http://localhost:8080"

ENV_FILE="${OASIS_ENV_FILE:-}"
if [ -z "$ENV_FILE" ]; then
    if [ -f .env ]; then
        ENV_FILE="$SCRIPT_DIR/.env"
    elif [ -r /etc/oasis-hotel/oasis.env ]; then
        ENV_FILE="/etc/oasis-hotel/oasis.env"
    else
        echo "❌ Environment tidak ditemukan." >&2
        echo "   Buat .env atau /etc/oasis-hotel/oasis.env terlebih dahulu." >&2
        exit 1
    fi
fi

if [ ! -r "$ENV_FILE" ]; then
    echo "❌ Environment file tidak dapat dibaca: $ENV_FILE" >&2
    exit 1
fi

ENV_FILE="$(realpath "$ENV_FILE")"
export OASIS_ENV_FILE="$ENV_FILE"
COMPOSE_BIN+=( --env-file "$ENV_FILE" )

if grep -q '^APP_KEY=$' "$ENV_FILE"; then
    if [ ! -w "$ENV_FILE" ]; then
        echo "❌ APP_KEY kosong dan environment file tidak dapat ditulis." >&2
        echo "   Isi APP_KEY terlebih dahulu dengan: php artisan key:generate --show" >&2
        exit 1
    fi
    APP_KEY="base64:$(openssl rand -base64 32)"
    sed -i "s|^APP_KEY=$|APP_KEY=${APP_KEY}|" "$ENV_FILE"
fi

echo "🔐 Environment: $ENV_FILE"
DOCKER_APP_URL_VALUE="$(sed -n 's/^DOCKER_APP_URL=//p' "$ENV_FILE" | tail -n 1)"
DOCKER_DATABASE_MODE="$(sed -n 's/^DOCKER_DATABASE_MODE=//p' "$ENV_FILE" | tail -n 1)"
DOCKER_DATABASE_MODE="${DOCKER_DATABASE_MODE:-external}"

if [ "$DOCKER_DATABASE_MODE" = "local" ]; then
    POSTGRES_PASSWORD_VALUE="$(sed -n 's/^POSTGRES_PASSWORD=//p' "$ENV_FILE" | tail -n 1)"
    if [ -z "$POSTGRES_PASSWORD_VALUE" ] || [ "$POSTGRES_PASSWORD_VALUE" = "change-this-strong-password" ]; then
        echo "❌ Mode local-db membutuhkan POSTGRES_PASSWORD yang kuat di .env." >&2
        exit 1
    fi
    COMPOSE_BIN+=( -f docker-compose.yaml -f docker-compose.local-db.yaml --profile local-db )
    echo "🗄️ Mode database: PostgreSQL container lokal"
else
    echo "☁️ Mode database: eksternal dari konfigurasi DB_* di .env"
fi

"${COMPOSE_BIN[@]}" down --remove-orphans || true
"${COMPOSE_BIN[@]}" up -d --build --wait
"${COMPOSE_BIN[@]}" exec -T web1 php artisan migrate --force
"${COMPOSE_BIN[@]}" ps

echo "✅ Stack deployment selesai"
echo "🌐 Akses aplikasi melalui ${DOCKER_APP_URL_VALUE:-http://localhost:8080}"
echo "🔍 Coba akses beberapa kali untuk melihat pembagian traffic antar node"
echo "   for i in 1 2 3 4 5 6; do curl -sI http://localhost:8080 | grep X-App-Node; done"
