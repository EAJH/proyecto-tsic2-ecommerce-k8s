FROM php:8.2-apache
# Instala las extensiones para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli
 
# Habilitar mod_rewrite para URLs amigables (.htaccess)
RUN a2enmod rewrite

