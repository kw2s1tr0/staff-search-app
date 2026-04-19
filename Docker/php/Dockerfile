FROM php:8.5-apache-bookworm

RUN apt-get update && \
    apt-get install -y \
    curl \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    default-mysql-client && \
    rm -rf /var/lib/apt/lists/*

COPY --from=node:24-bookworm /usr/local/bin/node /usr/local/bin/node
COPY --from=node:24-bookworm /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm && \
    ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /ssapp

RUN a2enmod rewrite

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN npm install -g playwright @playwright/cli && \
    playwright install --with-deps chromium