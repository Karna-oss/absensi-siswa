#!/bin/sh
set -e

# Tunggu database siap nerima koneksi
until php artisan db:show > /dev/null 2>&1; do
  echo "Menunggu database siap..."
  sleep 2
done

# Jalankan migration otomatis
php artisan migrate --force

# Lanjut jalankan command aslinya (php-fpm)
exec "$@"
