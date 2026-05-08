FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
    wget && \
    docker-php-ext-install mysqli && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mysqli

# Create directory for Adminer
RUN mkdir -p /var/www/adminer

# Download Adminer
RUN wget -O /var/www/adminer/index.php \
    https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php

# Set permissions
RUN chown -R www-data:www-data /var/www/adminer

# Set proper start command
CMD ["php-fpm"]

# Expose PHP-FPM port
EXPOSE 9000
