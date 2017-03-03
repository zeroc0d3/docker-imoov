#
#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
#
# To edit the 'workspace' base Image, visit its repository on Github
#    https://github.com/LaraDock/workspace
#
# To change its version, see the available Tags on the Docker Hub:
#    https://hub.docker.com/r/laradock/workspace/tags/
#

FROM laradock/workspace:1.3

MAINTAINER ZeroC0D3 Team <zeroc0d3.team@gmail.com>

#
#--------------------------------------------------------------------------
# Mandatory Software's Installation
#--------------------------------------------------------------------------
#
# Mandatory Software's such as ("php7.0-cli", "git", "vim", ....) are
# installed on the base image 'zeroc0d3/workspace' image. If you want
# to add more Software's or remove existing one, you need to edit the
# base image (https://github.com/LaraDock/workspace).
#

#
#--------------------------------------------------------------------------
# Optional Software's Installation
#--------------------------------------------------------------------------
#
# Optional Software's will only be installed if you set them to `true`
# in the `docker-compose.yml` before the build.
# Example:
#   - INSTALL_NODE=false
#   - ...
#

#####################################
# Non-Root User:
#####################################

# Add a non-root user to prevent files being created with root permissions on host machine.
ARG PUID=1000
ARG PGID=1000
RUN groupadd -g $PGID zeroc0d3 && \
    useradd -u $PUID -g zeroc0d3 -m zeroc0d3


#####################################
# Set Timezone
#####################################

ARG TZ=UTC
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#####################################
# Composer:
#####################################

# Add the composer.json
COPY ./composer.json /home/zeroc0d3/.composer/composer.json

# Make sure that ~/.composer belongs to zeroc0d3
RUN chown -R zeroc0d3:zeroc0d3 /home/zeroc0d3/.composer
USER zeroc0d3

# Check if global install need to be ran
ARG COMPOSER_GLOBAL_INSTALL=false
ENV COMPOSER_GLOBAL_INSTALL ${COMPOSER_GLOBAL_INSTALL}
RUN if [ ${COMPOSER_GLOBAL_INSTALL} = true ]; then \
    # run the install
    composer global install \
;fi

# Export composer vendor path
RUN echo "" >> ~/.bashrc && \
echo 'export PATH="~/.composer/vendor/bin:$PATH"' >> ~/.bashrc

#####################################
# Crontab
#####################################
USER root

# COPY ./crontab /etc/cron.d
# RUN chmod -R 644 /etc/cron.d

#####################################
# User Aliases
#####################################

USER zeroc0d3
COPY ./aliases.sh /home/zeroc0d3/aliases.sh
RUN echo "" >> ~/.bashrc && \
    echo "# Load Custom Aliases" >> ~/.bashrc && \
    echo "source /home/zeroc0d3/aliases.sh" >> ~/.bashrc && \
    echo "" >> ~/.bashrc
    
USER root
RUN echo "" >> ~/.bashrc && \
    echo "# Load Custom Aliases" >> ~/.bashrc && \
    echo "source /home/zeroc0d3/aliases.sh" >> ~/.bashrc && \
    echo "" >> ~/.bashrc

#####################################
# xDebug:
#####################################

ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    # Install the xdebug extension
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
;fi

#####################################
# PHP REDIS EXTENSION FOR PHP 7.0
#####################################
ARG INSTALL_PHPREDIS=false
RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
    # Install Php Redis Extension
    pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \
;fi

# Copy xdebug configration for remote debugging
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

#####################################
# MongoDB:
#####################################

ARG INSTALL_MONGO=false
RUN if [ ${INSTALL_MONGO} = true ]; then \
    # Install the mongodb extension
    pecl install mongodb && \
    docker-php-ext-enable mongodb \
;fi

#####################################
# ZipArchive:
#####################################

ARG INSTALL_ZIP_ARCHIVE=false
RUN if [ ${INSTALL_ZIP_ARCHIVE} = true ]; then \
    # Install the zip extension
    pecl install zip && \
    docker-php-ext-enable zip \
;fi

#####################################
# bcmath:
#####################################

ARG INSTALL_BCMATH=false
RUN if [ ${INSTALL_BCMATH} = true ]; then \
    # Install the bcmath extension
    docker-php-ext-install bcmath \
;fi

#####################################
# PHP Memcached:
#####################################

ARG INSTALL_MEMCACHED=false
RUN if [ ${INSTALL_MEMCACHED} = true ]; then \
    # Install the php memcached extension
    curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p memcached \
    && tar -C memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && ( \
        cd memcached \
        && phpize \
        && ./configure \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r memcached \
    && rm /tmp/memcached.tar.gz \
    && docker-php-ext-enable memcached \
;fi

#####################################
# Exif:
#####################################

ARG INSTALL_EXIF=false
RUN if [ ${INSTALL_EXIF} = true ]; then \
    # Enable Exif PHP extentions requirements
    docker-php-ext-install exif && \
    docker-php-ext-enable exif \
;fi

#####################################
# ssh:
#####################################
ARG INSTALL_WORKSPACE_SSH=false
ENV INSTALL_WORKSPACE_SSH ${INSTALL_WORKSPACE_SSH}

ADD insecure_id_rsa /tmp/id_rsa
ADD insecure_id_rsa.pub /tmp/id_rsa.pub

RUN if [ ${INSTALL_WORKSPACE_SSH} = true ]; then \
    rm -f /etc/service/sshd/down && \
    cat /tmp/id_rsa.pub >> /root/.ssh/authorized_keys \
        && cat /tmp/id_rsa.pub >> /root/.ssh/id_rsa.pub \
        && cat /tmp/id_rsa >> /root/.ssh/id_rsa \
        && rm -f /tmp/id_rsa* \
        && chmod 644 /root/.ssh/authorized_keys /root/.ssh/id_rsa.pub \
    && chmod 400 /root/.ssh/id_rsa \
;fi

#####################################
# Node / NVM:
#####################################

# Check if NVM needs to be installed
ARG NODE_VERSION=stable
ENV NODE_VERSION ${NODE_VERSION}
ARG INSTALL_NODE=false
ENV INSTALL_NODE ${INSTALL_NODE}
ENV NVM_DIR /home/zeroc0d3/.nvm
RUN if [ ${INSTALL_NODE} = true ]; then \
    # Install nvm (A Node Version Manager)
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.6/install.sh | bash && \
        . $NVM_DIR/nvm.sh && \
        nvm install ${NODE_VERSION} && \
        nvm use ${NODE_VERSION} && \
        nvm alias ${NODE_VERSION} && \
        npm install -g gulp bower vue-cli \
;fi

# Wouldn't execute when added to the RUN statement in the above block
# Source NVM when loading bash since ~/.profile isn't loaded on non-login shell
RUN if [ ${INSTALL_NODE} = true ]; then \
    echo "" >> ~/.bashrc && \
    echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm' >> ~/.bashrc \
;fi

# Add NVM binaries to root's .bashrc
USER root

RUN if [ ${INSTALL_NODE} = true ]; then \
    echo "" >> ~/.bashrc && \
    echo 'export NVM_DIR="/home/zeroc0d3/.nvm"' >> ~/.bashrc && \
    echo '[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm' >> ~/.bashrc \
;fi

#####################################
# YARN:
#####################################

USER zeroc0d3

ARG INSTALL_YARN=false
ENV INSTALL_YARN ${INSTALL_YARN}
ARG YARN_VERSION=latest
ENV YARN_VERSION ${YARN_VERSION}

RUN if [ ${INSTALL_YARN} = true ]; then \
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && \
    if [ ${YARN_VERSION} = "latest" ]; then \
        curl -o- -L https://yarnpkg.com/install.sh | bash; \
    else \
        curl -o- -L https://yarnpkg.com/install.sh | bash -s -- --version ${YARN_VERSION}; \
    fi && \
    echo "" >> ~/.bashrc && \
    echo 'export PATH="$HOME/.yarn/bin:$PATH"' >> ~/.bashrc \
;fi

# Add YARN binaries to root's .bashrc
USER root

RUN if [ ${INSTALL_YARN} = true ]; then \
    echo "" >> ~/.bashrc && \
    echo 'export YARN_DIR="/home/zeroc0d3/.yarn"' >> ~/.bashrc && \
    echo 'export PATH="$YARN_DIR/bin:$PATH"' >> ~/.bashrc \
;fi

#####################################
# PHP V8JS:
#####################################
USER root

ARG INSTALL_V8JS_EXTENSION=false
ENV INSTALL_V8JS_EXTENSION ${INSTALL_V8JS_EXTENSION}

RUN if [ ${INSTALL_V8JS_EXTENSION} = true ]; then \
    # Install the php V8JS extension
    add-apt-repository -y ppa:pinepain/libv8-5.4 \
    && apt-get update \
    && apt-get install -y php-dev php-pear libv8-5.4 \
    && pecl install v8js \
    && echo "extension=v8js.so" >> /etc/php/7.0/cli/php.ini \
;fi

#####################################
# Non-root user : PHPUnit path
#####################################

# add ./vendor/bin to non-root user's bashrc (needed for phpunit)
USER zeroc0d3

RUN echo "" >> ~/.bashrc && \
    echo 'export PATH="/var/www/vendor/bin:$PATH"' >> ~/.bashrc

#####################################
# Laravel Artisan Alias
#####################################
USER root

RUN echo "" >> ~/.bashrc && \
    echo 'alias art="php artisan"' >> ~/.bashrc

#####################################
# Laravel Envoy:
#####################################
USER zeroc0d3

ARG INSTALL_LARAVEL_ENVOY=true
ENV INSTALL_LARAVEL_ENVOY ${INSTALL_LARAVEL_ENVOY}

RUN if [ ${INSTALL_LARAVEL_ENVOY} = true ]; then \
    # Install the Laravel Envoy
    composer global require "laravel/envoy=~1.0" \
;fi

#####################################
# Deployer:
#####################################
USER zeroc0d3

ARG INSTALL_DEPLOYER=false
ENV INSTALL_DEPLOYER ${INSTALL_DEPLOYER}

RUN if [ ${INSTALL_DEPLOYER} = true ]; then \
    # Install the Deployer
    composer global require "deployer/deployer" \
;fi

#####################################
# Linuxbrew:
#####################################
USER root

ARG INSTALL_LINUXBREW=true
ENV INSTALL_LINUXBREW ${INSTALL_LINUXBREW}

RUN if [ ${INSTALL_LINUXBREW} = true ]; then \

    # Preparation
    apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y build-essential make cmake scons curl git \
      ruby autoconf automake autoconf-archive \
      gettext libtool flex bison \
      libbz2-dev libcurl4-openssl-dev \
      libexpat-dev libncurses-dev && \

    # Install the Linuxbrew
    git clone https://github.com/Homebrew/linuxbrew.git ~/.linuxbrew && \

    echo "" >> ~/.bashrc && \
    echo 'export PKG_CONFIG_PATH"=/usr/local/lib/pkgconfig:/usr/local/lib64/pkgconfig:/usr/lib64/pkgconfig:/usr/lib/pkgconfig:/usr/lib/x86_64-linux-gnu/pkgconfig:/usr/lib64/pkgconfig:/usr/share/pkgconfig:$PKG_CONFIG_PATH"' >> ~/.bashrc && \

    # Setup linuxbrew
    echo 'export LINUXBREWHOME="$HOME/.linuxbrew"' >> ~/.bashrc && \
    echo 'export PATH="$LINUXBREWHOME/bin:$PATH"' >> ~/.bashrc && \
    echo 'export MANPATH="$LINUXBREWHOME/man:$MANPATH"' >> ~/.bashrc && \
    echo 'export PKG_CONFIG_PATH="$LINUXBREWHOME/lib64/pkgconfig:$LINUXBREWHOME/lib/pkgconfig:$PKG_CONFIG_PATH"' >> ~/.bashrc && \
    echo 'export LD_LIBRARY_PATH="$LINUXBREWHOME/lib64:$LINUXBREWHOME/lib:$LD_LIBRARY_PATH"' >> ~/.bashrc \
;fi

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

# Clean up
USER root
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set default work directory
WORKDIR /var/www
