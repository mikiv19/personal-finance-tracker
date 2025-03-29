FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql
    RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer
    RUN a2enmod rewrite

# Configure Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html
COPY . /var/www/html

COPY . .
