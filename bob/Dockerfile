FROM debian:jessie-backports
MAINTAINER Jumia TechOps <techops@jumia.com>

WORKDIR /app/

ENV \
  DEBIAN_FRONTEND="noninteractive" \
  NR_INSTALL_SILENT="true"

RUN apt-get update && \
    apt-get install -yq --no-install-recommends \
        supervisor \
        ca-certificates \
        libc6 \
        libgcc1 \
        libjson-c2 \
        libstdc++6 \
        libyaml-0-2 \
        curl && \
    curl -k -s https://www.dotdeb.org/dotdeb.gpg | apt-key add - && \
    echo "deb http://packages.dotdeb.org jessie all" > /etc/apt/sources.list.d/dotdeb.list && \
    curl -k -s https://download.newrelic.com/548C16BF.gpg | apt-key add - && \
    echo "deb http://apt.newrelic.com/debian/ newrelic non-free" > /etc/apt/sources.list.d/newrelic.list && \
    curl -k -s http://nginx.org/keys/nginx_signing.key | apt-key add - && \
    echo "deb http://nginx.org/packages/debian/ jessie nginx" > /etc/apt/sources.list.d/nginx.list && \
    apt-get update && \
    apt-get -yq install --no-install-recommends \
        librabbitmq1 \
        libmemcached11 \
        libmemcachedutil2 \
        libsnappy1 \
        php7.0-bcmath \
        php7.0-cli \
        php7.0-curl \
        php7.0-fpm \
        php7.0-igbinary \
        php7.0-json \
        php7.0-mbstring \
        php7.0-mcrypt \
        php7.0-mysql \
        php7.0-memcached \
        php7.0-sqlite \
        php7.0-tidy \
        php7.0-zip \
        php7.0-xml \
        php7.0-xmlrpc \
        php7.0-xsl \
        newrelic-php5 \
        nginx && \
    # CLEAN UP
    mkdir -p /etc/nginx/sites-enabled && \
    apt-get purge -y curl && apt-get clean && apt-get autoremove -yq --purge && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ADD dependencies/ /deps/
RUN dpkg -i /deps/*.deb && rm -rf /deps

ADD etc /etc

ADD https://github.com/kelseyhightower/confd/releases/download/v0.11.0/confd-0.11.0-linux-amd64 /bin/confd
RUN chmod a+x /bin/confd

ADD https://github.com/marcelosousaalmeida/nginx-php-fpm-prometheus/releases/download/0.0.2-alpha/nginx-php-fpm-prometheus_0.0.2-alpha_linux_amd64 /bin/nginx-php-fpm-prometheus
RUN chmod a+x /bin/nginx-php-fpm-prometheus

ENV \
  ENVIRONMENT=dev \
  DOMAIN="local.dev" \
  TIMEZONE="Europe/Berlin"

CMD /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
