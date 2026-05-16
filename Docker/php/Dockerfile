FROM php:8.5-apache-bookworm

RUN apt-get update && \
    apt-get install -y \
    curl \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
    bubblewrap && \
    rm -rf /var/lib/apt/lists/*

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

RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    python3 \
    python3-pip \
    python3-venv \
    && rm -rf /var/lib/apt/lists/*

RUN npm install -g @openai/codex