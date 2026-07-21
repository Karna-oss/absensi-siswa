#!/bin/bash
set -e

echo "==> Menunggu database siap..."
until php artisan db:show > /dev/null 2>&1; do
  echo "    DB belum siap, retry dalam 2 detik..."
  sleep 2
done
echo "==> Database siap."

# Generate APP_KEY kalau belum ada (aman dijalankan berulang, tidak overwrite yang sudah ada)
if [ -z "$APP_KEY" ]; then
  echo "==> APP_KEY kosong, generate baru..."
  php artisan key:generate --force
fi

echo "==> Menjalankan migration..."
php artisan migrate --force

echo "==> Seed data awal (dilewati kalau sudah ada data / redeploy)..."
php artisan db:seed --force 2>&1 | grep -v "UNIQUE constraint\|Duplicate entry" || true

echo "==> Link storage..."
php artisan storage:link || true

echo "==> Cache config, route, view..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Setup selesai, menjalankan proses utama..."
exec "$@"