FROM php:8.0-fpm

# set main params

ARG BUILD_ARGUMENT_ENV=dev
ENV ENV=$BUILD_ARGUMENT_ENV
ENV APP_HOME /var/www/html
ARG UID=1000
ARG GID=1000
ENV USERNAME=www-data


# install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      procps \
      nano \
      git \
      unzip \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      supervisor \
      cron \
      sudo \
      libzip-dev \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
      pdo_mysql \
      sockets \
      intl \
      opcache \
      zip \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

# install redis extension
RUN pecl install redis && docker-php-ext-enable redis

# create document root, fix permissions for www-data user and change owner to www-data
RUN mkdir -p $APP_HOME/public && \
    mkdir -p /home/$USERNAME && chown $USERNAME:$USERNAME /home/$USERNAME \
    && usermod -u $UID $USERNAME -d /home/$USERNAME \
    && groupmod -g $GID $USERNAME \
    && chown -R ${USERNAME}:${USERNAME} $APP_HOME

# put php config for Laravel
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini


# install Xdebug in case dev/test environment
COPY ./docker/php/xdebug/do_we_need_xdebug.sh /tmp/
COPY ./docker/php/xdebug.ini /tmp/
RUN chmod u+x /tmp/do_we_need_xdebug.sh && /tmp/do_we_need_xdebug.sh

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# set working directory
WORKDIR $APP_HOME

USER ${USERNAME}

# copy source files and config file
COPY --chown=${USERNAME}:${USERNAME} . $APP_HOME/
COPY --chown=${USERNAME}:${USERNAME} .env $APP_HOME/.env

USER root