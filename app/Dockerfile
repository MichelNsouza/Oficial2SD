FROM php:8.2-fpm

# 1. Instalar dependências do sistema PRIMEIRO
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Instalar extensões PHP ANTES do Composer
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip sockets

# 3. AGORA SIM instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Definir workdir
WORKDIR /var/www/html

# 5. Por último, instalar pacotes PHP (GD já está disponível)
RUN composer require php-amqplib/php-amqplib:^3.5 phpoffice/phpspreadsheet:^1.29

# 6. Permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]