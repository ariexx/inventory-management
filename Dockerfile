FROM php:8.2-fpm

# Install system dependencies
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
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Configure PHP-FPM to listen on 0.0.0.0:9000
RUN echo '[www]' > /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'user = www-data' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'group = www-data' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'listen = 0.0.0.0:9000' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.max_children = 20' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.start_servers = 3' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.min_spare_servers = 2' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'pm.max_spare_servers = 4' >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]
