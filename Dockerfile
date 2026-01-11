FROM php:8.1-apache
COPY . /var/www/html
EXPOSE 80
RUN echo "{}" > /var/www/html/ ips.json && chmod 66 /var/www/html/ ips.json
