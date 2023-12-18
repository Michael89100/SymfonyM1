FROM php:8.2-fpm AS build

## Installation des utilitaires
RUN apt-get update && apt-get install -y curl vim nano git zip npm
RUN npm install -g yarn


## Config Timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime


## PHP Extensions & Composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd exif pdo_mysql intl zip bcmath && install-php-extensions @composer

## WHTMLTOPDF
#RUN curl -LO https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.buster_amd64.deb && \
#    apt-get update && apt-get install -y --no-install-recommends ./wkhtmltox_0.12.6-1.buster_amd64.deb && \
#   rm wkhtmltox_0.12.6-1.buster_amd64.deb


WORKDIR /var/www/app