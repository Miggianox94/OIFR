# See https://github.com/docker-library/php/blob/master/7.1/fpm/Dockerfile
#FROM php:7.1-fpm
FROM php:7.2.6-fpm
ARG TIMEZONE

MAINTAINER Maxence POUTORD <maxence.poutord@gmail.com>

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
#RUN echo "Setting permission for folders.."
#RUN chown -R www-data:www-data var/cache var/logs var/sessions

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
RUN "date"

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql


# install xdebug
##RUN pecl install xdebug
##RUN docker-php-ext-enable xdebug
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN echo "xdebug.remote_log=\"/tmp/xdebug.log\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_handler=\"dbgp\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_host=\"host.docker.internal\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_host=192.168.42.167" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_handler=\"dbgp\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install zip
RUN apt-get update && \
     apt-get install -y \
         zlib1g-dev \
         && docker-php-ext-install zip
		 
# Install Imagick
RUN apt-get update && apt-get install -y libmagickwand-6.q16-dev --no-install-recommends \
 && ln -s /usr/lib/x86_64-linux-gnu/ImageMagick-6.8.9/bin-Q16/MagickWand-config /usr/bin \
 && pecl install imagick \
 && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini

RUN echo 'alias sf="php app/console"' >> ~/.bashrc
RUN echo 'alias sf3="php bin/console"' >> ~/.bashrc

RUN echo "Changing user to uid 1000 and www-data"
RUN usermod -u 1000 www-data

RUN echo "copying source code...."
ADD symfony /var/www/symfony/
RUN mkdir -p -m 777 /var/www/symfony/web/uploads/tmpUnzipImages
RUN mkdir -p -m 777 /var/www/symfony/web/uploads/zipImages
RUN cd /var/www/symfony/ && composer install
RUN echo "Showing situation of symfony folder after COPY command.."
RUN ls -l /var/www/symfony/
#RUN cp -r /var/www/symfony/web/css /var/www/symfony/
#RUN cp -r /var/www/symfony/web/fonts /var/www/symfony/
#RUN cp -r /var/www/symfony/web/img /var/www/symfony/
#RUN cp -r /var/www/symfony/web/js /var/www/symfony/
#RUN cp -r /var/www/symfony/web/uploads /var/www/symfony/
RUN echo "Setting permission for folders symfony.."
RUN chown -R www-data:www-data /var/www/symfony
RUN chmod -R 777 /var/www/symfony
RUN ls -ld /var/www/symfony/
RUN ls -l /var/www/symfony/
RUN echo "Source code correctly copied"

WORKDIR /var/www/symfony
