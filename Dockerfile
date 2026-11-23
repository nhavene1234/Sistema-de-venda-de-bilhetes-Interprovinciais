# Dockerfile - MozTransport (PHP + Apache) para o Render
FROM php:8.2-apache

# Extensão PostgreSQL (Neon)
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

# Apache: activar .htaccess (backups/ e armazenamento/ usam "Require all denied")
# e escutar na porta que o Render indica através da variável PORT
ENV PORT=10000
RUN a2enmod rewrite headers \
 && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
 && sed -ri 's/^Listen 80$/Listen ${PORT}/' /etc/apache2/ports.conf \
 && sed -ri 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf \
 && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
 && a2enconf servername

# PHP em modo produção + limites de upload (o sistema aceita fotos até 5 MB)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && printf 'upload_max_filesize=8M\npost_max_size=10M\ndate.timezone=Africa/Maputo\n' > "$PHP_INI_DIR/conf.d/app.ini"

# O projecto usa BASE_URL = '/bilhetes', por isso fica nessa pasta
# e a raiz do site redirecciona para ela
COPY . /var/www/html/bilhetes/
RUN printf '<?php header("Location: /bilhetes/"); exit;' > /var/www/html/index.php \
 && mkdir -p /var/www/html/bilhetes/armazenamento/livretes \
             /var/www/html/bilhetes/armazenamento/cartas \
             /var/www/html/bilhetes/backups \
 && chown -R www-data:www-data /var/www/html/bilhetes/armazenamento /var/www/html/bilhetes/backups

# Opcional, recomendado em produção: remover scripts de desenvolvimento
# RUN rm -rf /var/www/html/bilhetes/instalar /var/www/html/bilhetes/teste.php /var/www/html/bilhetes/teste_hora.php

EXPOSE 10000
