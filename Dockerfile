# =========================================================
# Dockerfile - Laravel (absensi-siswa) untuk deploy di Coolify
# Single image: PHP-FPM + Nginx + Supervisor jadi satu container
# =========================================================

# ---------- STAGE 1: build asset frontend (Vite) ----------
FROM node:20-alpine AS node-build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---------- STAGE 2: install dependency PHP (composer) ----------
FROM composer:2 AS composer-build
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs

COPY . .
COPY --from=node-build /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev

# ---------- STAGE 3: runtime image ----------
FROM php:8.2-fpm-alpine

# System dependencies + PHP extensions yang dipakai Laravel
RUN apk add --no-cache \
        nginx \
        supervisor \
        bash \
        curl \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

WORKDIR /var/www

# Salin hasil build dari stage composer (sudah termasuk vendor/ & public/build)
COPY --from=composer-build /app ./

# Konfigurasi Nginx, Supervisor, entrypoint
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permission untuk storage & cache Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
