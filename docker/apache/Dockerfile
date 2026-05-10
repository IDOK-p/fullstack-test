FROM php:7.4-apache

# Apache config
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
    && rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-install \
    bz2 \
    intl \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    mysqli

# Apache config (VirtualHost)
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Включение модулей Apache
RUN a2enmod rewrite headers

# PHP config
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# ✅ УСТАНОВКА COMPOSER (исправлено)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем проект
COPY src/ /var/www/html/

# Создание пользователя (чтобы не было проблем с правами)
ARG uid=1000
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser \
    && mkdir -p /home/devuser/.composer \
    && chown -R devuser:devuser /home/devuser

# Права на проект (иногда критично)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
