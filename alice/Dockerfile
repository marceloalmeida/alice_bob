FROM debian:jessie-backports
MAINTAINER Jumia TechOps <techops@jumia.com>

WORKDIR /app/

ENV \
  DEBIAN_FRONTEND="noninteractive" \
  NR_INSTALL_SILENT="true" \
  UNZIP="python -c \"import zipfile,sys,StringIO;zipfile.ZipFile(StringIO.StringIO(sys.stdin.read())).extractall(sys.argv[1] if len(sys.argv) == 2 else '.')\""

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
        php7.0-cli \
        php7.0-curl \
        php7.0-fpm \
        php7.0-igbinary \
        php7.0-json \
        php7.0-mbstring \
        php7.0-memcached \
        php7.0-zip \
        php7.0-xml \
        newrelic-php5 \
        nginx && \
    curl -s https://releases.hashicorp.com/serf/0.8.0/serf_0.8.0_linux_amd64.zip | sh -c "$UNZIP /usr/local/bin/" && \
    chmod a+x /usr/local/bin/serf && \
    # CLEAN UP
    mkdir -p /etc/nginx/sites-enabled && \
    apt-get purge -y curl && apt-get clean && apt-get autoremove -yq --purge && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ADD dependencies/ /deps/
ADD https://github.com/marcelosousaalmeida/libmustache4-builddeb/releases/download/v0.4.2/libmustache4_0.4.2-1_amd64.deb /deps/
ADD https://github.com/marcelosousaalmeida/php-mustache-builddeb/releases/download/v0.7.2/php7.0-mustache_0.7.2-1_amd64.deb /deps/
ADD http://packages.couchbase.com/ubuntu/pool/jessie/main/libc/libcouchbase/libcouchbase2-core_2.6.3-1_amd64.deb /deps/
ADD https://github.com/marcelosousaalmeida/php-couchbase-builddeb/releases/download/v2.2.3/php7.0-couchbase_2.2.3-aig-1_amd64.deb /deps/
RUN dpkg -i /deps/*.deb && rm -rf /deps

ADD etc /etc

ADD https://github.com/kelseyhightower/confd/releases/download/v0.11.0/confd-0.11.0-linux-amd64 /bin/confd
RUN chmod a+x /bin/confd

ADD https://github.com/marcelosousaalmeida/nginx-php-fpm-prometheus/releases/download/0.0.2-alpha/nginx-php-fpm-prometheus_0.0.2-alpha_linux_amd64 /bin/nginx-php-fpm-prometheus
RUN chmod a+x /bin/nginx-php-fpm-prometheus

ENV \
  ALICE_COUNTRY_ISO=local \
  ALICE_ENVIRONMENT=dev \
  ALICE_DOMAIN="local.dev" \
  ALICE_TIMEZONE="Europe/Berlin" \
  ALICE_SESSION_SAVEHANDLER="files" \
  ALICE_SESSION_SAVEPATH="/var/lib/php/sessions"

CMD /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
