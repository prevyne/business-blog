# Use an official PHP image as the base image
FROM php:8.2-apache

# Set the working directory
WORKDIR /var/www/html

# Copy the PHP project files into the container
COPY . .

# Install any necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]