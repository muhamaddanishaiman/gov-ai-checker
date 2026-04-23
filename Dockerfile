# ==============================================================================
# Stage 1: Install PHP dependencies
# ==============================================================================
FROM composer:2 AS composer-builder

WORKDIR /app

COPY gov-ai/composer.json gov-ai/composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

COPY gov-ai/ .
RUN composer dump-autoload --optimize --no-dev

# ==============================================================================
# Stage 2: Build frontend assets
# ==============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY gov-ai/package.json gov-ai/package-lock.json ./
RUN npm ci

COPY gov-ai/vite.config.js ./
COPY gov-ai/resources/ ./resources/

# Tailwind CSS v4 scans vendor for blade pagination views (app.css line 3)
# Copy the vendor from composer stage so Tailwind can find the source files
COPY --from=composer-builder /app/vendor ./vendor

# Also need storage/framework/views for Tailwind source scanning (app.css line 4)
RUN mkdir -p storage/framework/views

RUN npm run build

# ==============================================================================
# Stage 3: Production image
# ==============================================================================
FROM php:8.2-apache

# Install PHP extensions needed by Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_sqlite zip bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Default port (overridden at runtime by entrypoint)
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
ENV PORT=8080

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY gov-ai/docker/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

# Copy Laravel app
COPY gov-ai/ .

# Copy vendor from composer stage
COPY --from=composer-builder /app/vendor ./vendor

# Copy built Vite assets from frontend stage
COPY --from=frontend-builder /app/public/build ./public/build

# Create required directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    database

# Create SQLite database
RUN touch database/database.sqlite

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache database
RUN chmod -R 775 storage bootstrap/cache database

# Copy entrypoint and fix Windows CRLF line endings
COPY gov-ai/docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

CMD ["/usr/local/bin/entrypoint.sh"]
