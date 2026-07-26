#!/usr/bin/env bash
set -e

cd /var/www/html

# Generate APP_KEY kalau belum ada (aman dipanggil berkali-kali)
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# Cache config, route, view untuk performa production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migration otomatis tiap deploy
php artisan migrate --force

# Buat symlink storage (untuk file upload publik)
php artisan storage:link || true

exec "$@"
