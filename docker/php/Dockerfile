FROM php:8.3-fpm

ARG user=laravel_app
ARG uid=1000

# ===> Instalações de dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# ===> Extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

# ===> Composer (multi-stage copy)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ===> Usuário com permissões apropriadas
RUN useradd -G www-data,root -u "$uid" -d /home/"$user" "$user" \
 && mkdir -p /home/"$user"/.composer \
 && chown -R "$user":"$user" /home/"$user"

# ===> Redis via PECL
RUN pecl install -o -f redis \
 && rm -rf /tmp/pear \
 && docker-php-ext-enable redis

# ===> Copiar e permitir execução do entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# ===> Definir diretório de trabalho
WORKDIR /var/www

# ===> Mudar para o usuário configurado
USER $user

# ===> Entrypoint e comando default
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
