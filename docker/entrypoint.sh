#!/bin/sh
set -e

# Tunggu database siap nerima koneksi
until php artisan migrate:status > /dev/null 2>&1 || [ $? -eq 1 ]; do
  echo "Menunggu database siap..."
  sleep 2
done

# Jalankan migration otomatis
php artisan migrate --force

# Lanjut jalankan command aslinya (php-fpm)
exec "$@"
