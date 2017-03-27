FROM php:5.6-apache

RUN DEBIAN_FRONTEND=nonintercative

RUN apt-get -qq update

#MySQL
RUN echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
RUN echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
RUN apt-get install -y mysql-server mysql-client

RUN service mysql start && mysql -uroot -proot -e "CREATE DATABASE rest_api"
#RUN service mysql start && mysql -uroot -proot -e "SHOW DATABASES"


#RUN aptitude purge dpkg -l | grep php| awk '{print $2}'
#RUN aptitude purge dpkg -l | grep php| awk '{print $2}' |tr "\n" " "
#RUN add-apt-repository ppa:ondrej/php
RUN apt-get update

#RUN apt-get -qq -y install \
#		   php5.6 \
#		   php5.6-mysql \
		   #libapache2-mod-php5 \
#		   apache2


#Config Apache
#ADD foreground.sh /etc/apache2/foreground.sh
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2


# Install deps
ENV APP_HOME /app
ENV HOME /root
RUN mkdir $APP_HOME
WORKDIR $APP_HOME
COPY composer.json* $APP_HOME/

# Upload source
COPY . $APP_HOME
RUN service mysql start && mysql -uroot -proot rest_api < bdd.sql

# Supervisor Config
#RUN /bin/bash -c /usr/bin/easy_install supervisor
#RUN /bin/bash -c /usr/bin/easy_install supervisor-stdout

RUN mkdir -p /var/lock/apache2 /var/run/apache2 /var/run/sshd /var/log/supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
#ADD supervisord.conf /etc/supervisord.conf

COPY ./configs/apache2.conf ${APACHE_CONF_DIR}/apache2.conf
COPY ./configs/app.conf ${APACHE_CONF_DIR}/sites-enabled/app.conf

#RUN mkdir -p /var/log/supervisor/
#ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
#ADD start.sh /start.sh
#RUN chmod 755 /start.sh

# Start server
ENV PORT 8080
EXPOSE 8080

CMD ["./entrypoint.sh"]
#CMD ["/usr/bin/supervisord"]
#CMD ["/bin/bash", "-e", "/start.sh"]
