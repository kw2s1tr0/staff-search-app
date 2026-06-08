FROM node:24-bookworm-slim AS node

FROM php:8.5-apache-bookworm

RUN apt-get update && \
    apt-get install -y \
    curl \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    libatomic1 \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /ssapp

RUN a2enmod rewrite

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln -s ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm && \
    ln -s ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx && \
    ln -s ../lib/node_modules/corepack/dist/corepack.js /usr/local/bin/corepack

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-venv \
    && rm -rf /var/lib/apt/lists/*

RUN npm install -g @openai/codex
