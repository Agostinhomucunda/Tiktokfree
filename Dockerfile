FROM php:8.1-apache
COPY . /var/www/html
EXPOSE 80
RUN touch /var/www/html/ips.json &&
chomod 66 /var/www/html/ips.json
