# Use PHP 8.3 with FPM and Alpine for smaller image size
FROM php:8.3-fpm-alpine AS base

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev dependencies for production)
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts

# Copy package files
COPY package.json package-lock.json* ./

# Install Node dependencies (need dev deps for building)
RUN npm ci

# Copy application files
COPY . .

# Build frontend assets
RUN npm run build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Production stage
FROM php:8.3-fpm-alpine

# Install minimal dependencies for runtime
RUN apk add --no-cache \
    libpng \
    libzip \
    oniguruma

# Copy PHP extensions from base
COPY --from=base /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=base /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Copy application from base
COPY --from=base --chown=www-data:www-data /var/www/html /var/www/html

WORKDIR /var/www/html

# Expose port 8000 (Laravel's default)
EXPOSE 8000

# Create entrypoint script
RUN echo '#!/bin/sh' > /entrypoint.sh && \
    echo 'php artisan config:cache' >> /entrypoint.sh && \
    echo 'php artisan route:cache' >> /entrypoint.sh && \
    echo 'php artisan view:cache' >> /entrypoint.sh && \
    echo 'php artisan storage:link || true' >> /entrypoint.sh && \
    echo 'exec php artisan serve --host=0.0.0.0 --port=8000' >> /entrypoint.sh && \
    chmod +x /entrypoint.sh

# Use www-data user
USER www-data

# Start the application
ENTRYPOINT ["/entrypoint.sh"]

