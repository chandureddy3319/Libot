# Use the official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Copy project files to the Apache server root
COPY library/ /var/www/html/

# Enable Apache mod_rewrite (optional, for pretty URLs)
RUN a2enmod rewrite

# Ensure uploads directory exists and set permissions
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Expose port 80
EXPOSE 80 