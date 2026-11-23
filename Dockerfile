FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev libxml2-dev unzip \
    && docker-php-ext-install mysqli pdo_mysql zip \
    && php -m | grep -qi simplexml \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod headers rewrite

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-faultline.ini
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html

RUN mkdir -p /var/www/html/uploads /var/www/html/themes /var/www/html/logs /flag \
    && printf '%s\n' 'FLT{command_injection_8c4f}' > /flag/cmd.txt \
    && printf '%s\n' 'FLT{local_file_include_2f7b}' > /flag/lfi.txt \
    && printf '%s\n' 'FLT{unrestricted_upload_9d5a}' > /flag/upload.txt \
    && printf '%s\n' 'FLT{php_object_inject_3a6d}' > /flag/unserialize.txt \
    && printf '%s\n' 'FLT{xxe_entity_read_2b8f}' > /flag/xxe.txt \
    && printf '%s\n' 'FLT{predictable_reset_1d6c}' > /flag/reset.txt \
    && printf '%s\n' 'FLT{cookie_steal_xss_5a1d}' > /flag/xss.txt \
    && printf '%s\n' 'FLT{coupon_reuse_6b2e}' > /flag/logic.txt \
    && printf '%s\n' 'FLT{alg_none_jwt_4f8c}' > /flag/jwt.txt \
    && printf '%s\n' 'FLT{remember_me_admin_7e2a}' > /flag/session.txt \
    && printf '%s\n' 'FLT{log_viewer_xss_2d4a}' > /flag/logxss.txt \
    && printf '%s\n' 'FLT{fail_open_auth_6e8b}' > /flag/failopen.txt \
    && printf '%s\n' 'FLT{verbose_error_path_1b7c}' > /flag/errors.txt \
    && printf '%s\n' 'FLT{cors_wildcard_6e3d}' > /flag/cors.txt \
    && chown -R www-data:www-data /var/www/html /flag \
    && chmod -R 775 /var/www/html/uploads /var/www/html/themes /var/www/html/logs

EXPOSE 80
