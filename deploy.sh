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

install_server_environment() {
    if [ ! -t 0 ]; then
        echo "❌ Setup environment interaktif membutuhkan terminal." >&2
        echo "   Gunakan OASIS_ENV_FILE=/lokasi/file.env ./deploy.sh untuk proses non-interaktif." >&2
        exit 1
    fi

    local temp_file deploy_group line app_key required_key
    temp_file="$(mktemp)"
    ENV_TMP_FILE="$temp_file"
    deploy_group="$(id -gn)"
    trap 'stty echo 2>/dev/null || true; rm -f "${ENV_TMP_FILE:-}"' EXIT INT TERM

    echo
    echo "🔐 SETUP ENVIRONMENT PERTAMA"
    echo "Paste seluruh isi .env kamu di bawah ini."
    echo "Input disembunyikan agar API key tidak terlihat di terminal."
    echo "Setelah selesai, ketik __OASIS_ENV_END__ pada baris baru lalu tekan Enter."
    echo

    stty -echo
    while IFS= read -r line; do
        if [ "$line" = "__OASIS_ENV_END__" ]; then
            break
        fi
        printf '%s\n' "$line" >> "$temp_file"
    done
    stty echo
    echo

    for required_key in DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
        if ! grep -q "^${required_key}=." "$temp_file"; then
            echo "❌ Konfigurasi wajib belum tersedia: ${required_key}" >&2
            exit 1
        fi
    done

    app_key="$(sed -n 's/^APP_KEY=//p' "$temp_file" | tail -n 1)"
    if [ -z "$app_key" ]; then
        app_key="base64:$(openssl rand -base64 32)"
        if grep -q '^APP_KEY=' "$temp_file"; then
            sed -i "s|^APP_KEY=.*$|APP_KEY=${app_key}|" "$temp_file"
        else
            printf '\nAPP_KEY=%s\n' "$app_key" >> "$temp_file"
        fi
    fi

    if [ "$(id -u)" -eq 0 ]; then
        install -d -m 750 -o root -g "$deploy_group" /etc/oasis-hotel
        install -m 640 -o root -g "$deploy_group" "$temp_file" /etc/oasis-hotel/oasis.env
    else
        sudo install -d -m 750 -o root -g "$deploy_group" /etc/oasis-hotel
        sudo install -m 640 -o root -g "$deploy_group" "$temp_file" /etc/oasis-hotel/oasis.env
    fi

    rm -f "$temp_file"
    ENV_TMP_FILE=""
    trap - EXIT INT TERM
    ENV_FILE="/etc/oasis-hotel/oasis.env"
    echo "✅ Environment tersimpan aman di $ENV_FILE"
}

ENV_FILE="${OASIS_ENV_FILE:-}"

if [ "${1:-}" = "--setup-env" ]; then
    install_server_environment
fi

if [ -z "$ENV_FILE" ]; then
    if [ -f .env ]; then
        ENV_FILE="$SCRIPT_DIR/.env"
    elif [ -r /etc/oasis-hotel/oasis.env ]; then
        ENV_FILE="/etc/oasis-hotel/oasis.env"
    else
        install_server_environment
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
