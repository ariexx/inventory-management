# Gunakan image dasar PHP 8.2 dengan FPM
FROM php:8.2-fpm

# Instalasi dependensi sistem yang dibutuhkan
# Node.js untuk build aset, netcat untuk health check, mariadb-client untuk koneksi CLI
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    mariadb-client \
    nodejs \
    npm \
    netcat-traditional \
    fcgiwrap \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Salin binary Composer dari image resmi (praktik terbaik)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja
WORKDIR /var/www

# Salin file dependensi terlebih dahulu untuk memanfaatkan Docker layer caching
COPY composer.json composer.lock ./
# Install dependensi PHP untuk produksi
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Salin file dependensi Node.js
COPY package.json package-lock.json ./
# Install dependensi NPM dengan 'ci' yang lebih cepat dan konsisten untuk CI/CD
RUN npm ci

# FIX: Salin sisa kode aplikasi setelah dependensi di-install
# Ini akan menggunakan file .dockerignore untuk mengecualikan file yang tidak perlu
COPY . .

# Build aset front-end untuk produksi
RUN npm run build

# Hapus node_modules setelah build untuk mengurangi ukuran image
RUN rm -rf node_modules

# FIX: Hapus pembuatan file .env. Ini harus ditangani saat runtime di host.

# Atur kepemilikan dan izin yang benar untuk Laravel
# Pengguna www-data (yang menjalankan PHP-FPM) harus memiliki akses tulis
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# IMPROVEMENT: Skrip health check yang lebih andal
# Ini akan memeriksa apakah FPM benar-benar dapat memproses permintaan PHP
RUN echo '#!/bin/sh' > /usr/local/bin/health-check.sh \
    && echo 'set -e' >> /usr/local/bin/health-check.sh \
    && echo "SCRIPT_NAME=/health SCRIPT_FILENAME=/health REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000" >> /usr/local/bin/health-check.sh \
    && chmod +x /usr/local/bin/health-check.sh

# Expose port yang digunakan oleh PHP-FPM
EXPOSE 9000

# Jalankan PHP-FPM sebagai perintah utama
CMD ["php-fpm"]
