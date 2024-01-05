FROM php:8.3-alpine
RUN apk --no-cache update && apk --no-cache upgrade
RUN apk --no-cache add libzip-dev libcurl curl-dev libpng libpng-dev libwebp libwebp-dev libjpeg-turbo libjpeg-turbo-dev autoconf g++ make sqlite-dev libxml2-dev linux-headers

RUN docker-php-ext-install curl gd pdo pdo_mysql pdo_sqlite simplexml zip

RUN pecl install xdebug \
    && echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/20-xdebug.ini \
    && echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/20-xdebug.ini \
    && echo "xdebug.client_host=172.21.0.1" >> /usr/local/etc/php/conf.d/20-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/20-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/20-xdebug.ini

RUN docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --2.6 --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www
