FROM php:7.4.7-fpm

RUN apt-get update -y && \
    apt-get install --no-install-recommends -y bzip2 git libonig-dev libpq-dev libxml2-dev nginx libmemcached-dev libzip-dev zlib1g-dev && \
    docker-php-ext-install bcmath ctype json mbstring mysqli pdo pdo_mysql tokenizer xml zip && \
    pecl install memcached && \
    docker-php-ext-enable memcached && \
    docker-php-ext-enable mysqli && \
    docker-php-ext-enable opcache && \
    apt-get remove -y libonig-dev libpq-dev libxml2-dev libmemcached-dev libzip-dev zlib1g-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY php.ini /usr/local/etc/php/conf.d/php.override.ini
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
EXPOSE 80
ENTRYPOINT ["/entrypoint.sh"]
