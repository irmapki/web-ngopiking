#!/usr/bin/env bash
set -e

# Pastikan hanya 1 MPM yang aktif (jaga-jaga kalau ada yang re-enable mpm_event saat runtime)
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Railway kasih env PORT secara dinamis, arahkan Apache untuk listen di port itu
: "${PORT:=80}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-enabled/*.conf

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
