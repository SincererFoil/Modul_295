FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite


ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN printf '<Directory /var/www/html/public>\nFallbackResource /index.php\n</Directory>\n' > /etc/apache2/conf-available/slim.conf
RUN a2enconf slim

WORKDIR /var/www/html