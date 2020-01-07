FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y --force-yes unzip git ssl-cert net-tools zip libzip-dev libpq-dev libpq5 nmap

RUN docker-php-ext-install pdo pgsql pdo_pgsql bcmath

RUN curl -Ss https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

RUN a2enmod rewrite && \
    a2dissite 000-default

COPY conf/app.conf /etc/apache2/sites-available/

RUN pecl install zip && \
    docker-php-ext-enable zip

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.enable=1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.remote_host=\"10.20.30.40\"" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/php.ini

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/php.ini && \
    echo "display_startup_errors = On" >> /usr/local/etc/php/php.ini

ENV XDEBUG_CONFIG idekey=PHPSTORM

RUN a2ensite app && \
    a2enmod headers

CMD /usr/sbin/apache2ctl -D FOREGROUND
