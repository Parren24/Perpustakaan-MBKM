# Use PHP 8.2 image as base
FROM chialab/php:8.2-apache

ENV ACCEPT_EULA=Y

WORKDIR /var/www/html

# Update packages and install necessary dependencies
RUN apt-get update \
    && apt-get install -y git zip unzip apt-utils gnupg2 curl \
    && apt-get clean

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install MS ODBC Driver for SQL Server and PHP extensions
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/22.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 unixodbc-dev \
    && pecl install sqlsrv pdo_sqlsrv \
    && echo "extension=sqlsrv.so" > /usr/local/etc/php/conf.d/sqlsrv.ini \
    && echo "extension=pdo_sqlsrv.so" > /usr/local/etc/php/conf.d/pdo_sqlsrv.ini \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install Node.js (version 18.x) and a compatible npm version
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@9.8.1 \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Jalankan composer install dan npm install di dalam container
# Verify installation
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 775 /var/www/html/

RUN php -v && node -v && npm -v

