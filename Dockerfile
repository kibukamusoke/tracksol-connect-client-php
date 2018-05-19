FROM php:7.0-apache
COPY public_html/ /var/www/html/
COPY config/php.ini /usr/local/etc/php/
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install pdo pdo_pgsql && docker-php-ext-enable pdo_pgsql
RUN a2enmod rewrite && service apache2 restart