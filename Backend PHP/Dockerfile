FROM php:8.2-apache

# Habilitar PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite

# Permitir que Apache lea y obedezca el archivo .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar el código del proyecto al contenedor
COPY . /var/www/html/

EXPOSE 80