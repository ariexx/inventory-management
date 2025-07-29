FROM php:8.2-fpm

# Install system dependencies + Node.js + netcat for health checks
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    mariadb-client nodejs npm netcat-traditional \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files for caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package.json and install npm
COPY package*.json ./
RUN npm ci

# Copy all files
COPY . /var/www

# Build assets
RUN npm run build

# Clean up node_modules
RUN rm -rf node_modules

# Create .env from example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Set permissions - critical for PHP-FPM
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Don't override the default PHP-FPM config completely
# Just add our custom pool configuration
RUN echo '; Custom PHP-FPM pool configuration' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.max_children = 20' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.start_servers = 3' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.min_spare_servers = 2' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.max_spare_servers = 4' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.max_requests = 200' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'catch_workers_output = yes' >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Create a simple health check script
RUN echo '#!/bin/bash' > /usr/local/bin/health-check.sh \
    && echo 'nc -z 127.0.0.1 9000 && php-fpm -t' >> /usr/local/bin/health-check.sh \
    && chmod +x /usr/local/bin/health-check.sh

EXPOSE 9000
CMD ["php-fpm"]
