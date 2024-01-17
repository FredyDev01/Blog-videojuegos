FROM php:8.0.28-apache

# Instalar dependencias de mysqli
RUN apt update \
    && apt install -y libzip-dev default-libmysqlclient-dev default-mysql-client \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli


# Habilitar mod_rewrite
RUN a2enmod rewrite

# Cambiar el puerto de Apache a 8080
RUN sed -ri -e 's!:80>!:8080>!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!Listen 80!Listen 8080!g' /etc/apache2/ports.conf

# Copiar archivos y ajustar permisos
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

# Configurar DirectoryIndex y Options
RUN echo "<Directory /var/www/html>" >> /etc/apache2/apache2.conf
RUN echo "  Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf
RUN echo "  AllowOverride All" >> /etc/apache2/apache2.conf
RUN echo "  Require all granted" >> /etc/apache2/apache2.conf
RUN echo "</Directory>" >> /etc/apache2/apache2.conf

RUN echo "AccessFileName .htaccess" >> /etc/apache2/apache2.conf

EXPOSE 8080
