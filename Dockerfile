FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Caso você tenha um arquivo .env.example, pode copiar ele para .env
RUN cp .env.example .env

# Gera a chave do aplicativo Laravel
RUN php artisan key:generate

# Rodar as migrações (criar banco de dados)
RUN php artisan migrate --force

CMD ["php-fpm"]
