FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo pdo_mysql zip
RUN apt-get update && apt-get install -y unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet
RUN wget https://get.symfony.com/cli/installer -O - -q | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN useradd -d /home/develop -m -s /bin/bash develop && echo "develop:develop" | chpasswd && adduser develop sudo
WORKDIR /project
RUN chown -R develop:develop /project
USER develop