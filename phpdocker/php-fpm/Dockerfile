FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        git \ 
        openssh-server \ 
        php8.2-gd \ 
        php8.2-memcached \ 
        php8.2-pgsql \ 
        php8.2-redis; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer