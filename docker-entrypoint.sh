#!/bin/sh

# Copiar .env se não existir
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Instalar dependências do Laravel
composer install --no-interaction --optimize-autoloader

# Gerar chave da aplicação
php artisan key:generate

# Definir permissões corretas
chmod -R 777 storage bootstrap/cache

exec "$@"
