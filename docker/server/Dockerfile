FROM php:8.2-apache

# Aggiungi i driver per PostgreSQL
RUN apt-get update

# Copia la configurazione di Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copia il tuo progetto PHP nella directory di Apache
COPY ./ /var/www/html

# Aggiungi Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Abilita mod_rewrite di Apache
RUN a2enmod rewrite

# Riavvia Apache
RUN /etc/init.d/apache2 restart

# Aggiungi Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Installa Git e Zip
RUN apt-get install -y git zip
