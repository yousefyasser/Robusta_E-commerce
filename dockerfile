# Use PHP image as base image
FROM php:latest

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Clear cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip mysqli

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . /var/www/html

# Install PHP dependencies
RUN composer install

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Generate application key and JWT secret
RUN php artisan key:generate && php artisan jwt:secret

# start application
ENTRYPOINT ["sh", "-c", "php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=8000"]