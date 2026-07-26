#!/usr/bin/env bash
set -e

cd /var/www/html

# APP_KEY harus sudah diisi lewat Environment Variable di platform (Railway/Render),
# karena file .env sengaja tidak ikut masuk ke dalam container.
if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY belum diisi. Tambahkan environment variable APP_KEY di platform deploy kamu."
  exit 1
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
