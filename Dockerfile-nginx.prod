FROM debian:jessie

MAINTAINER Maxence POUTORD <maxence.poutord@gmail.com>

RUN apt-get update && apt-get install -y \
    nginx
	
#RUN echo "###########Cloning git repo...."
#RUN apt-get install -y git
#RUN git clone https://github.com/Miggianox94/OIFR.git


RUN usermod -u 1000 www-data
COPY nginx/nginx.conf /etc/nginx/
COPY nginx/symfony.conf /etc/nginx/sites-available/

RUN echo "###########Showing situation of /etc/nginx/.."
RUN ls -l /etc/nginx/

RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony
RUN rm /etc/nginx/sites-enabled/default

RUN echo "###########upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN echo "###########Changing user to uid 1000 and www-data"
RUN usermod -u 1000 www-data

RUN echo "###########Copying source code...."
ADD symfony /var/www/symfony/
RUN echo "###########Showing situation of symfony folder after COPY command.."
RUN ls -l /var/www/symfony/
RUN echo "###########Setting permission for folders symfony.."
RUN chown -R www-data:www-data /var/www/symfony
RUN chmod -R 777 /var/www/symfony
RUN ls -ld /var/www/symfony/
RUN ls -l /var/www/symfony/
RUN echo "###########Source code correctly copied"

WORKDIR /var/www/symfony

CMD ["nginx"]

RUN echo "###########Showing situation at the end of /etc/nginx/.."
RUN ls -l /etc/nginx/

EXPOSE 80
EXPOSE 443