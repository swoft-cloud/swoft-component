FROM swoft/swoft:latest

LABEL maintainer="huangzhhui <h@swoft.org>"

WORKDIR /var/www/swoft
RUN composer install \
    && composer dump-autoload -o \
    && composer clearcache
