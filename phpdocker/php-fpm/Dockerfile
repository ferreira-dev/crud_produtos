FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.2-gd \
        php8.2-http \
        php8.2-mysql \
        php8.2-redis \
        php8.2-raphf \
        php8.2-mbstring \
        php8.2-dev \
        php8.2-cli \
        php-pear \
        build-essential \
        pkg-config \
        php8.2-curl \
        php8.2-xml \
        gnupg2 \

    && echo "extension=/usr/lib/php/20220829/dom.so" > /etc/php/8.2/fpm/conf.d/20-dom.ini \
    && echo "extension=/usr/lib/php/20220829/xml.so" > /etc/php/8.2/fpm/conf.d/20-xml.ini \
    && echo "extension=/usr/lib/php/20220829/xmlreader.so" > /etc/php/8.2/fpm/conf.d/20-xmlreader.ini \
    && echo "extension=/usr/lib/php/20220829/xmlwriter.so" > /etc/php/8.2/fpm/conf.d/20-xmlwriter.ini \
    && echo "extension=/usr/lib/php/20220829/curl.so" > /etc/php/8.2/fpm/conf.d/20-curl.ini \
    && echo "extension=/usr/lib/php/20220829/mbstring.so" > /etc/php/8.2/fpm/conf.d/20-mbstring.ini \
    && echo "extension=/usr/lib/php/20220829/mysqli.so" > /etc/php/8.2/fpm/conf.d/20-mysqli.ini \
    && echo "extension=/usr/lib/php/20220829/pdo_mysql.so" > /etc/php/8.2/fpm/conf.d/20-pdo_mysql.ini \

    && echo "extension=/usr/lib/php/20220829/dom.so" > /etc/php/8.2/cli/conf.d/20-dom.ini \
    && echo "extension=/usr/lib/php/20220829/xml.so" > /etc/php/8.2/cli/conf.d/20-xml.ini \
    && echo "extension=/usr/lib/php/20220829/xmlreader.so" > /etc/php/8.2/cli/conf.d/20-xmlreader.ini \
    && echo "extension=/usr/lib/php/20220829/xmlwriter.so" > /etc/php/8.2/cli/conf.d/20-xmlwriter.ini \
    && echo "extension=/usr/lib/php/20220829/curl.so" > /etc/php/8.2/cli/conf.d/20-curl.ini \
    && echo "extension=/usr/lib/php/20220829/mbstring.so" > /etc/php/8.2/cli/conf.d/20-mbstring.ini \
    && echo "extension=/usr/lib/php/20220829/mysqli.so" > /etc/php/8.2/cli/conf.d/20-mysqli.ini \
    && echo "extension=/usr/lib/php/20220829/pdo_mysql.so" > /etc/php/8.2/cli/conf.d/20-pdo_mysql.ini \
    && echo "extension=/usr/lib/php/20220829/zip.so" > /etc/php/8.2/cli/conf.d/20-zip.ini \

    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY . /application
