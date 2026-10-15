FROM php:8.2-apache

# mysqli bővítmény az adatbázishoz
RUN docker-php-ext-install mysqli

COPY . /var/www/html/
