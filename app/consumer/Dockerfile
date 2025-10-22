FROM php:8.2-cli

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
WORKDIR /app

# 5. Copiar composer.json
COPY composer.json ./

# 6. Instalar dependências (GD já está disponível)
RUN composer install --no-dev --optimize-autoloader

# 7. Copiar script
COPY consumer.php .

CMD ["php", "consumer.php"]