FROM ubuntu:18.04

LABEL maintainer="Jake Gully"

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
  && apt-get install -y gnupg tzdata \
  && echo "UTC" > /etc/timezone \
  && dpkg-reconfigure -f noninteractive tzdata

RUN apt-get update\
  && apt-get install -y software-properties-common \
  && add-apt-repository ppa:ondrej/php

RUN apt-get update \
  && apt-get install -y curl zip unzip git supervisor sqlite3 \
  nginx php7.3-fpm php7.3-cli \
  php7.3-pgsql php7.3-sqlite3 php7.3-gd \
  php7.3-curl php7.3-memcached \
  php7.3-imap php7.3-mysql php7.3-mbstring \
  php7.3-xml php7.3-zip php7.3-bcmath php7.3-soap \
  php7.3-intl php7.3-readline php7.3-xdebug \
  php-msgpack php-igbinary \
  && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
  && mkdir /run/php \
  && apt-get -y autoremove \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
  && echo "daemon off;" >> /etc/nginx/nginx.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
  && ln -sf /dev/stderr /var/log/nginx/error.log

ADD default /etc/nginx/sites-available/default
ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD php-fpm.conf /etc/php/7.3/fpm/php-fpm.conf
ADD start-container /usr/bin/start-container
RUN chmod +x /usr/bin/start-container

ENTRYPOINT ["/usr/bin/start-container"]
# ENTRYPOINT ["bash"]
