#!/bin/bash
set -e

echo "==> Menunggu database siap (host: $DB_HOST, port: $DB_PORT, db: $DB_DATABASE)..."
TRIES=0
until php -r "
try {
    \$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    exit(0);
} catch (Exception \$e) {
    fwrite(STDERR, \$e->getMessage() . PHP_EOL);
    exit(1);
}
"; do
  TRIES=$((TRIES+1))
  echo "    DB belum siap (percobaan ke-$TRIES), retry dalam 2 detik..."
  if [ "$TRIES" -ge 15 ]; then
    echo "==> GAGAL konek setelah $TRIES percobaan. Pesan error terakhir di atas ⬆️"
    exit 1
  fi
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



