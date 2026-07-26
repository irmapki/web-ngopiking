#!/bin/bash
set -e

echo "=== Memastikan hanya 1 MPM aktif (prefork) ==="
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

echo "=== Setup Laravel ==="

echo "-> Cek storage link..."
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
else
    echo "Storage link sudah ada, dilewati."
fi

echo "-> Menjalankan migration..."
php artisan migrate --force

echo "-> Cache config, route, view..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Cek konfigurasi Apache sebelum start ==="
apache2ctl configtest

echo "=== Starting Apache ==="
exec "$@"
