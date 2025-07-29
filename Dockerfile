FROM php:8.2-fpm

# Install system dependencies + Node.js
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    mariadb-client nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

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

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Configure PHP-FPM to listen on port 9000
RUN echo '[global]' > /usr/local/etc/php-fpm.conf \
    && echo 'error_log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.conf \
    && echo '[www]' >> /usr/local/etc/php-fpm.conf \
    && echo 'user = www-data' >> /usr/local/etc/php-fpm.conf \
    && echo 'group = www-data' >> /usr/local/etc/php-fpm.conf \
    && echo 'listen = 0.0.0.0:9000' >> /usr/local/etc/php-fpm.conf \
    && echo 'listen.mode = 0666' >> /usr/local/etc/php-fpm.conf \
    && echo 'pm = dynamic' >> /usr/local/etc/php-fpm.conf \
    && echo 'pm.max_children = 20' >> /usr/local/etc/php-fpm.conf \
    && echo 'pm.start_servers = 3' >> /usr/local/etc/php-fpm.conf \
    && echo 'pm.min_spare_servers = 2' >> /usr/local/etc/php-fpm.conf \
    && echo 'pm.max_spare_servers = 4' >> /usr/local/etc/php-fpm.conf \
    && echo 'pm.max_requests = 200' >> /usr/local/etc/php-fpm.conf \
    && echo 'catch_workers_output = yes' >> /usr/local/etc/php-fpm.conf \
    && echo 'access.log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.conf

EXPOSE 9000
CMD ["php-fpm"]
