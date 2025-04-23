# Use PHP 8.1 CLI as base image
FROM php:8.1-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /app

# Copy application files
COPY . /app

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 777 /app/storage /app/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port for Laravel
EXPOSE 8080

RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan config:cache

# Start Laravel application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
