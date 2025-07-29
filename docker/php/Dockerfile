FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . /app

# Ensure up-to-date autoload
RUN composer install --no-interaction --prefer-dist --no-dev

# Make console executable
RUN chmod +x bin/console

# Default entrypoint
ENTRYPOINT ["php", "bin/console"]
