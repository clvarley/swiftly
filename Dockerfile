FROM php:7.1.11-fpm-alpine3.4

ENV COMPOSER_ALLOW_SUPERUSER 1

# This is needed for running wpcli db commands
RUN apk add --update --no-cache --virtual .ext-deps mysql-client

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install mbstring

RUN apk add --update --no-cache --virtual .ext-deps \
    mysql-client \
    zip \
    libpng-dev \
    imagemagick-dev \
    libtool \
    git


RUN apk add --no-cache \
        $PHPIZE_DEPS \
    && pecl install mailparse \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-enable mailparse \
    && docker-php-ext-install gd

# Configure upload limits
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Install composer
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer -O - -q | php -- --quiet
RUN mv composer.phar /usr/local/bin/composer

# Add WP-CLI
RUN curl -o /bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x /bin/wp

# Move project files into container
COPY . /var/www/html/

# Configure permissions for moved files
# RUN find /var/www/html/ -type d -exec chmod 755 {} +
# RUN find /var/www/html/ -type f -exec chmod 644 {} +
# RUN chown -R www-data:www-data /var/www/html/wp-content
