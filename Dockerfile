# 1. Imagen base de PHP
# Usamos php-fpm (FastCGI Process Manager), que es más eficiente para Nginx.
FROM php:8.2-fpm-alpine

# 2. Instalar Extensiones
# Ejecutamos los comandos para instalar y habilitar las extensiones necesarias.
# apk add: es el administrador de paquetes de Alpine Linux (la base de nuestra imagen)
# docker-php-ext-install: es un script de ayuda de las imágenes de Docker de PHP
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql opcache \
    && docker-php-ext-enable opcache

# 3. Establecer el directorio de trabajo
# Esta es la carpeta donde Nginx buscará tus archivos.
WORKDIR /var/www/html

# 4. Copiar los archivos de la aplicación
# Copia todo el contenido de tu proyecto local al contenedor.
COPY . /var/www/html

# 5. Cambiar permisos (Importante para evitar errores de escritura/lectura)
RUN chown -R www-data:www-data /var/www/html

# 6. Exponer el puerto de PHP-FPM
# Este es el puerto interno que Nginx usará para comunicarse con PHP.
EXPOSE 9000

# 7. Definir el comando inicial (el proceso principal)
CMD ["php-fpm"]

# Usamos una imagen que ya incluye PHP-FPM y Nginx
FROM ghcr.io/railwayapp/nginx-php:8.2

# 1. Habilitar extensiones de PHP (Resuelve el error de mysqli)
# El comando 'install-php-extensions' está incluido en la imagen base.
RUN install-php-extensions mysqli pdo_mysql opcache

# 2. Copiar archivos de configuración de Nginx
# Esto reemplaza la configuración predeterminada de Nginx con tu configuración de URL amigables.
COPY default.conf /etc/nginx/sites-enabled/default.conf

# 3. Copiar los archivos de la aplicación al directorio de Nginx/PHP
# El directorio por defecto para la aplicación en esta imagen es /app
COPY . /app

# 4. Asegurar que Nginx sepa dónde está tu aplicación
WORKDIR /app

# El comando de arranque ya está en la imagen base (CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"])
# Por lo que no necesitas definir el CMD, ya que la base arranca ambos servicios.