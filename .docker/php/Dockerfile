FROM php:7.4-fpm

# Installing dependencies
RUN apt-get update && apt-get install -y \
        zip \
        libpq-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install the PHP iconv library
RUN docker-php-ext-install -j$(nproc) iconv
# Configure GD library
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
# Install the PHP GD library
RUN docker-php-ext-install -j$(nproc) gd

# Install the PHP pdo_pgsql extention
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
        && docker-php-ext-install -j$(nproc) \
        	pdo \
        	pdo_pgsql \
        	pgsql