#!/bin/sh
set -eu

APP_PORT="${PORT:-80}"

case "$APP_PORT" in
  ''|*[!0-9]*)
    echo "Invalid PORT value: $APP_PORT" >&2
    exit 1
    ;;
esac

sed -ri "s/^Listen [0-9]+$/Listen ${APP_PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${APP_PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ -n "${RENDER_EXTERNAL_HOSTNAME:-}" ]; then
  export APP_URL="${APP_URL:-https://${RENDER_EXTERNAL_HOSTNAME}}"
  export ASSET_URL="${ASSET_URL:-https://${RENDER_EXTERNAL_HOSTNAME}}"
fi

if [ -z "${APP_KEY:-}" ] && [ -n "${RENDER_APP_SECRET:-}" ]; then
  APP_KEY="$(php -r 'echo "base64:".base64_encode(hash("sha256", getenv("RENDER_APP_SECRET"), true));')"
  export APP_KEY
fi

if [ "${APP_ENV:-production}" = "production" ] && [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY is required in production." >&2
  exit 1
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan package:discover --ansi
php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
