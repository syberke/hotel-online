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
echo "🌐 Akses: http://localhost:8080 dan https://localhost:8443"

echo "🔐 Menggunakan sertifikat TLS lokal untuk HTTPS"

"${COMPOSE_BIN[@]}" down --remove-orphans || true
"${COMPOSE_BIN[@]}" up -d --build

echo "✅ Stack deployment selesai"
echo "🔍 Coba akses beberapa kali untuk melihat pembagian traffic antar node"
echo "   curl -k https://localhost:8443"
