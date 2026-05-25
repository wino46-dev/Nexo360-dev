FROM php:8.1.25-fpm

ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y apt-transport-https \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd


RUN apt-get update && apt-get install -y \
    sudo \
    libzip-dev \
    zlib1g-dev \
    libjpeg-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get update && apt-get install -y \
    sudo \
    libzip-dev \
    zlib1g-dev \
    zip \
    && docker-php-ext-install zip

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chmod 777 -R /var/www/
RUN chown -R www-data:www-data /var/www
#RUN chown -R www-data:www-data /tmp

# Set working directory
WORKDIR /var/www

RUN docker-php-ext-install mysqli pdo_mysql

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Cambiar Apache para escuchar en el puerto 81
RUN sed -i 's/80/81/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite si lo necesitas
RUN a2enmod rewrite

# Asegura que el contenedor tenga permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Copia inicial del código (será sobrescrito por volumen)
COPY ./public /var/www/html
