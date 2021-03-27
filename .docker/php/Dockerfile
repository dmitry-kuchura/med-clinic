FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
#ARG user
#ARG uid

# Installing dependencies
RUN apt-get update && apt-get install -y \
        firebird-dev \
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

# Install the PHP firebird extention
RUN docker-php-ext-install pdo_firebird

# Copy command scheduler
COPY ./scheduler.sh /scheduler/scheduler.sh

# Get latest Composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user