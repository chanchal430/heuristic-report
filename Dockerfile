FROM php:8.2-cli

WORKDIR /app

# Install system deps + Node
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install zip

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies & build assets
RUN npm install
RUN npm run build

# Laravel setup
RUN php artisan key:generate || true

# Optional: optimize (safe)
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache


EXPOSE 10000

CMD php -S 0.0.0.0:10000 -t public