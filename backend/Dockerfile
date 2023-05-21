 FROM php:7.3.0-apache

# Install dependencies
RUN docker-php-ext-install pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /Application-Test/backend

# Copy application files
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Expose port 8000
EXPOSE 8000

# Start Apache server
CMD php artisan serve --host=0.0.0.0 --port=8000 
