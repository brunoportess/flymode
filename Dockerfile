FROM php:8.2-fpm

# Instala as dependências do sistema
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmysqlclient-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos do projeto para o container
COPY . .

# Instala as dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Copia as variáveis de ambiente do Laravel (se necessário)
# Caso você tenha um arquivo .env.example, pode copiar ele para .env
RUN cp .env.example .env

# Gera a chave do aplicativo Laravel
RUN php artisan key:generate

# Rodar as migrações (criar banco de dados)
RUN php artisan migrate --force

# Inicia o PHP-FPM
CMD ["php-fpm"]
