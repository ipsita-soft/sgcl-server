# Use the official PHP image as a base image
FROM php:latest

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    libpq-dev # Add libpq-dev for PostgreSQL development headers

# Install PDO PostgreSQL extension
RUN docker-php-ext-install pdo pdo_pgsql

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy existing application directory contents to the container
COPY . .

# Expose port 80 to the outside world
EXPOSE 80

# Define the command to start the PHP server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
