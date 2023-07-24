# Use the official PHP image as the base image
FROM php:7.3-apache

# Set the working directory inside the container
WORKDIR /Application-Test/backend

# Copy all the PHP files from your local directory to the container
COPY . /Application-Test/backend

# Expose port 80 to access the PHP application
EXPOSE 80

# Start the PHP development server when the container starts
CMD ["php", "-S", "0.0.0.0:80"]