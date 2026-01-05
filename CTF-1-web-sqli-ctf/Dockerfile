# Dockerfile - corrigé pour s'assurer que la DB est créée dans /var/www/html/data
FROM php:8.2-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      build-essential \
      pkg-config \
      libsqlite3-dev \
      sqlite3 \
 && docker-php-ext-install pdo pdo_sqlite \
 && apt-get purge -y --auto-remove build-essential pkg-config libsqlite3-dev \
 && rm -rf /var/lib/apt/lists/*

COPY app/ /var/www/html/

COPY create_db.php /var/www/html/create_db.php
COPY README.md /var/www/html/README.md

WORKDIR /var/www/html

RUN mkdir -p data \
 && chown -R www-data:www-data /var/www/html \
 && php create_db.php \
 && chown -R www-data:www-data /var/www/html/data

EXPOSE 80
CMD ["apache2-foreground"]
