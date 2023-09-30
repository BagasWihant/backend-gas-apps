
FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql


RUN addgroup -g 1000  laravel
RUN adduser -G laravel -D -s /bin/sh -u 1000 laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf


USER laravel