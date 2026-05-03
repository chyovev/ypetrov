FROM php:8.4-fpm

# Installed packages:
# git and unzip are used by composer when installing dependencies
# libjpeg, libpng and the rest are prerequisite for PHP gd extension
RUN apt update && apt install -y \
        git \
        unzip \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libxpm-dev \
    # configure PHP extensions
    && docker-php-ext-configure \
        gd \
            --with-freetype \
            --with-jpeg \
            --with-webp \
            --with-xpm \
    # install additional PHP extensions
    # (some are already pre-installed by the base image)
    # gd is used for image manipulation
    # MySQL is used for the database
    # pcntl is used by the Laravel's queue worker
    && docker-php-ext-install \
        -j$(nproc) gd \
        mysqli \
        pdo_mysql \
        pcntl \
    # enable additional PHP extensions
    && docker-php-ext-enable \
        gd \
        mysqli \
        pdo_mysql \
        pcntl

# clean up apt cache to reduce image size
RUN apt clean && rm -rf /var/lib/apt/lists/*

# copy composer used to install dependencies
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer