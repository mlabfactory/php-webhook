FROM mlabfactory/php8-apache:v1.3

WORKDIR /var/www/workdir

COPY ./ /var/www/workdir
RUN touch .env

# setup apache
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

RUN chmod +x worker 
CMD ["./worker >> /var/www/workdir/storage/worker.log 2>&1 &"]