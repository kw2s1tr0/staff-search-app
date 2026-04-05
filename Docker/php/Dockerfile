FROM php:8.5-apache-bookworm

RUN apt update && \
    apt install -y \
    curl \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    default-mysql-client && \
    rm -rf /var/lib/apt/lists/*

RUN mkdir /ssapp

RUN a2enmod rewrite

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug