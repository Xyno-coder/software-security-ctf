FROM php:8.1-apache

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/logs 2>/dev/null || mkdir -p /var/www/html/logs && chmod -R 775 /var/www/html/logs && \
    echo "FLAG{PHP_Object_Injection_Lab_2025}" > /flag.txt && \
    chmod 644 /flag.txt

EXPOSE 80

CMD ["apache2-foreground"]
