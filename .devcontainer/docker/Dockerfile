FROM php:8.4-cli
COPY --from=ghcr.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install php extensions
RUN install-php-extensions gd xdebug pdo pdo_mysql mbstring xml bcmath exif curl intl

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer
