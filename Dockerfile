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
RUN npm ci --production

# Copy all files
COPY . /var/www

# Build assets
RUN npm run build

# Generate Laravel key (important!)
RUN php artisan key:generate --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Configure PHP-FPM
RUN echo '[www]' > /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'user = www-data' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'group = www-data' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'listen = 0.0.0.0:9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf

EXPOSE 9000
CMD ["php-fpm"]
