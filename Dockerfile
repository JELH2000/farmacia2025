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