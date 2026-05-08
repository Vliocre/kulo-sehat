FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    curl \
    zip \
    unzip \
    git \
    supervisor \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN npm install
RUN npm run build

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-dev --no-scripts --optimize-autoloader

RUN cp .env.example .env || true
RUN php artisan key:generate || true

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
