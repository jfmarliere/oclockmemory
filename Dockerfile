FROM mysql:5.7

ENV MYSQL_ROOT_PASSWORD root
ENV MYSQL_DATABASE oclockmemory

COPY ./sql/oclockmemory.sql /docker-entrypoint-initdb.d/

FROM php:7.4-apache as base

# Some variables
ENV USER application
ENV GROUP application
ENV APACHE_DOCUMENT_ROOT /var/www/html/

# Create user and group with ID 1000 (to execute commands with the same ID as host user in container and avoid permissions problems)
RUN groupadd -g 1000 ${GROUP}
RUN useradd --create-home -u 1000 -s /bin/bash -g 1000 ${USER}

# Replace default DocumentRoot to our project folder in Apache vhosts
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

# Fix bug since Debian 10 Buster when installing java package (https://github.com/geerlingguy/ansible-role-java/issues/64#issuecomment-393299088)
RUN mkdir -p /usr/share/man/man1

# Install some packages
RUN set -x \
	&& apt-get update && apt-get install -y \
        build-essential \
		dialog \
		apt-utils \
		vim \
		imagemagick \
		libldap-2.4-2 \
		libxslt1.1 \
		zlib1g \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libbz2-dev \
        libldap2-dev \
        libldb-dev \
        libxml2-dev \
        libxslt1-dev \
        zlib1g-dev \
        libpng-dev \
		zip \
        unzip \
        bzip2 \
        openssh-client \
        rsync \
        pdftk \
        xvfb \
        git \
        wget \
        wkhtmltopdf \
        gnupg \
        libmcrypt-dev \
        libmagick++-dev \
        libzip-dev \
        python3-pip \
        dos2unix

# PHP extensions
RUN docker-php-ext-install \
            mysqli \
            pdo_mysql \
            soap \
            xsl \
            xml \
            opcache \
            zip \
            gd \
            json \
            intl \
        && pecl install imagick-3.4.4 \
        && docker-php-ext-enable imagick

# Enable some Apache modules
RUN a2enmod rewrite negotiation headers ssl

# Add Composer bin folder in PATH system variable to execute composer packages binary files without typing full path
RUN echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> /etc/bash.bashrc

# Jump to web folder into container
WORKDIR /var/www/html

# Clean container
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

