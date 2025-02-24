# Usar una imagen base de PHP
FROM php:8.2.12-apache

# Instalar extensiones necesarias (como PDO y Composer)
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar el contenido de la aplicación al contenedor
COPY . /var/www/html/

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de PHP
RUN composer install

# Exponer el puerto 80
EXPOSE 80

# Configura Apache para usar el nombre de servidor
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Inicia Apache en primer plano (foreground) cuando se ejecute el contenedor
CMD ["apache2ctl", "-D", "FOREGROUND"]
