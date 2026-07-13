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
echo "🗄️ Database PostgreSQL akan dipasang sebagai service terpisah"
echo "🌐 Akses: http://localhost:8080"

if [ ! -f .env ]; then
    cp .env.example .env
fi

if grep -q '^APP_KEY=$' .env; then
    APP_KEY="base64:$(openssl rand -base64 32)"
    sed -i "s|^APP_KEY=$|APP_KEY=${APP_KEY}|" .env
fi

POSTGRES_PASSWORD_VALUE="$(sed -n 's/^POSTGRES_PASSWORD=//p' .env | tail -n 1)"
DOCKER_APP_URL_VALUE="$(sed -n 's/^DOCKER_APP_URL=//p' .env | tail -n 1)"
if [ -z "$POSTGRES_PASSWORD_VALUE" ] || [ "$POSTGRES_PASSWORD_VALUE" = "change-this-strong-password" ]; then
    echo "❌ Ubah POSTGRES_PASSWORD di .env sebelum deployment." >&2
    exit 1
fi

"${COMPOSE_BIN[@]}" down --remove-orphans || true
"${COMPOSE_BIN[@]}" up -d --build
"${COMPOSE_BIN[@]}" exec -T web1 php artisan migrate --force
"${COMPOSE_BIN[@]}" ps

echo "✅ Stack deployment selesai"
echo "🌐 Akses aplikasi melalui ${DOCKER_APP_URL_VALUE:-http://localhost:8080}"
echo "🔍 Coba akses beberapa kali untuk melihat pembagian traffic antar node"
echo "   for i in 1 2 3 4 5 6; do curl -sI http://localhost:8080 | grep X-App-Node; done"
