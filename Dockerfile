# ================================================================
# Dockerfile
# ================================================================

# syntax=docker/dockerfile:1
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nano \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Create system user
RUN useradd -G www-data,root -u 1000 -d /home/ticketuser ticketuser
RUN mkdir -p /home/ticketuser/.composer && \
    chown -R ticketuser:ticketuser /home/ticketuser

# Copy application files
COPY --chown=ticketuser:ticketuser . /var/www

# Set permissions
RUN chown -R ticketuser:ticketuser /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Switch to non-root user
USER ticketuser

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]