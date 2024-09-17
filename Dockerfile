FROM php:8.2-alpine
COPY . /var/www/html
WORKDIR /var/www/html
VOLUME /var/www/html
CMD ["php", "-S", "0.0.0.0:9000"]
