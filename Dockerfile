FROM php:8.2-apache

# Set environment variables
ENV APPLICATION_PORT 80
ENV APACHE_DOCUMENT_ROOT /var/www/html/lottery-api/public

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        zip \
        unzip \
        supervisor \
        libapache2-mod-security2 \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libicu-dev \
        libssl-dev \
        libzip-dev \
        zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip intl \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . /var/www/html/lottery-api
COPY .build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY .build/apache2.conf /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html/lottery-api

# Change apache document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure apache security
RUN echo "ServerSignature Off" >> /etc/apache2/apache2.conf
RUN echo "ServerTokens Full" >> /etc/apache2/apache2.conf
RUN echo 'SecServerSignature "Lottery"' >> /etc/apache2/mods-available/security2.conf

# Enable apache modules
RUN a2enmod rewrite
RUN a2enmod security2
RUN a2enmod deflate

RUN sed -i "s/80/$APPLICATION_PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Start supervisord
ENTRYPOINT [ "supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]
