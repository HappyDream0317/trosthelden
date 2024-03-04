FROM php:8.1-fpm-alpine

RUN apk add --no-cache nginx wget

RUN apk update update && apk add \
    git \
    curl \
    zip \
    unzip \
    vim

RUN docker-php-ext-install pdo_mysql mysqli exif

RUN apk add libpng-dev

RUN apk add \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN docker-php-ext-install gd

RUN apk update && apk add --no-cache supervisor
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
ARG NOVA_USERNAME
ARG NOVA_PASSWORD

ENV NOVA_USERNAME ${NOVA_USERNAME}
ENV NOVA_PASSWORD ${NOVA_PASSWORD}

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN cd /app && \
    /usr/local/bin/composer config http-basic.nova.laravel.com "${NOVA_USERNAME}" "${NOVA_PASSWORD}" && /usr/local/bin/composer install

#RUN chown -R www-data:www-data /app
RUN chmod -R 777 /app

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]
#CMD sh /app/docker/startup.sh