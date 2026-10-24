FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_sqlite pdo_mysql bcmath zip gd opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create non-root user (UID 1000) for Hugging Face Spaces compatibility
RUN useradd -m -u 1000 user
WORKDIR /home/user/app

# Default production environment variables
ENV APP_NAME="Natural Snacks Traceability" \
    APP_ENV=production \
    APP_KEY="base64:h5F2fyiTcPT0I1XLy/oEjmACbLjp+/bHK/8awq5B5qQ=" \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    SESSION_DRIVER=file \
    QUEUE_CONNECTION=sync \
    CACHE_STORE=file \
    PORT=7860

# Copy all application files with proper ownership
COPY --chown=user:user . .

# Copy and fix permissions on entrypoint
COPY --chown=user:user docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Switch to non-root user
USER user

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 7860

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
