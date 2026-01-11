FROM php:8.1-apache
COPY . /var/www/html
EXPOSE 80
RUN touch /var/www/html/ips.json && chmod 666 /var/www/html/ips.json
RUN chmod -R 755 /var/www/html
