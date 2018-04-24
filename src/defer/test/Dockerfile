FROM swoft/swoft:latest

MAINTAINER huangzhhui <h@swoft.org>

WORKDIR /var/www/swoft
RUN composer install \
    && composer dump-autoload -o \
    && composer clearcache
