FROM devilbox/php-fpm-8.1:latest


RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    docker-php-ext-enable apcu pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www
WORKDIR /var/www


#RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN cp ./deploy/env/.env.main .env
#
#RUN php artisan migrate:fresh --seed --force
#
#RUN php artisan cache:clear
#
#RUN php artisan route:cache
#
#RUN php artisan config:cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]
