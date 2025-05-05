#!/bin/sh

set -e

LOCK_FILE="/tmp/entrypoint.lock"

# Evitar execução repetida
if [ -f "$LOCK_FILE" ]; then
  echo "⚠️ Script já foi executado anteriormente. Ignorando..."
  exec "$@"
  exit 0
fi

touch "$LOCK_FILE"

cd /var/www

echo "🧪 Preparando ambiente Laravel..."

# ✅ Verificar e instalar dependências
if [ ! -d "vendor" ]; then
  echo "📦 Instalando dependências via Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# ✅ Copiar .env se não existir
if [ ! -f ".env" ]; then
  echo "⚙️ Copiando .env.example para .env"
  cp .env.example .env
fi

# ✅ Função segura para atualizar/inserir variável no .env
insert_or_replace_env() {
    VAR="$1"
    VALUE="$2"
    if grep -qE "^\s*#?\s*${VAR}=" .env; then
        sed -i "s|^\s*#\?\s*${VAR}=.*|${VAR}=${VALUE}|" .env
    else
        echo "${VAR}=${VALUE}" >> .env
    fi
}

echo "🛠️ Configurando banco de dados..."
insert_or_replace_env "DB_CONNECTION" "mysql"
insert_or_replace_env "DB_HOST" "laravel_mysql"
insert_or_replace_env "DB_PORT" "3306"
insert_or_replace_env "DB_DATABASE" "management_system"
insert_or_replace_env "DB_USERNAME" "user"
insert_or_replace_env "DB_PASSWORD" "password"

# ✅ Gerar chave se ausente
if ! grep -q '^APP_KEY=base64:' .env; then
  echo "🔐 Gerando chave da aplicação..."
  php artisan key:generate --force
fi

# ✅ Otimização (evita rodar em produção desnecessariamente)
echo "⚙️ Otimizando autoloader e cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# ✅ Rodar migrations se necessário
if ! php artisan migrate:status | grep -q '| Y |'; then
  echo "📊 Executando migrations..."
  php artisan migrate --force
  echo "🌱 Executando seed (primeira vez)..."
  php artisan db:seed --force || true
fi

# ✅ Corrigir permissões (resiliente)
echo "🔒 Corrigindo permissões..."
chmod -R ug+rw storage bootstrap/cache
chown -R "$(id -u):$(id -g)" storage bootstrap/cache || true

echo "✅ Ambiente Laravel pronto."

# ✅ Executar comando enviado via CMD do Docker
exec "$@"
