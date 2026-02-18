FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# Set working directory
WORKDIR /var/www

# Copy website files
COPY . /var/www

# Fix permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
