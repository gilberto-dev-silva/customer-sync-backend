#!/bin/sh

set -e

cd /var/www

echo "🧪 Preparando ambiente Laravel..."

# Instalar dependências
if [ ! -d "vendor" ]; then
  echo "📦 Instalando dependências via Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Copiar .env se não existir
if [ ! -f ".env" ]; then
  echo "⚙️ Copiando .env.example para .env"
  cp .env.example .env

  echo "🛠️ Aplicando configurações do banco MySQL..."

  sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
  sed -i 's/^DB_HOST=.*/DB_HOST=laravel_mysql/' .env
  sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env
  sed -i 's/^DB_DATABASE=.*/DB_DATABASE=management_system/' .env
  sed -i 's/^DB_USERNAME=.*/DB_USERNAME=user/' .env
  sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=password/' .env
fi

# Gerar chave de app
echo "🔐 Gerando chave da aplicação"
php artisan key:generate --force

# Rodar migrations + seeds
echo "📊 Executando migrations + seeds..."
php artisan migrate --force
php artisan db:seed --force || true

# Corrigir permissões
echo "🔒 Corrigindo permissões..."
chmod -R ug+rw storage bootstrap/cache
chown -R laravel:laravel storage bootstrap/cache

# Servidor embutido Laravel (ou pode trocar por php-fpm se preferir)
echo "🚀 Iniciando servidor Laravel..."
exec php artisan serve --host=0.0.0.0 --port=8000
