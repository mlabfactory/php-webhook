FROM mlabfactory/php8-apache:v1.3

WORKDIR /var/www/workdir

COPY ./ /var/www/workdir

# setup apache
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 9000