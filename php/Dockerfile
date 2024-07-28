# Use the official PHP image with Apache
FROM php:7.4-apache

RUN docker-php-ext-install mysqli

# Copy the PHP application into the container
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
