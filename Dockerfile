FROM alpine

RUN apk update && apk upgrade \
    && apk add --no-cache php83 php83-mbstring php83-xml php83-pdo php83-mysqli php83-bcmath php83-ctype php83-zip \
    php83-imap php83-curl php83-json php83-gettext php83-gd php83-session php83-snmp php83-pdo_mysql php83-tokenizer \
    php83-openssl php83-sockets php83-fileinfo php83-dom php83-exif php83-simplexml php83-xmlwriter php83-xmlreader\
    php83-sqlite3 php83-pdo_sqlite php83-pcntl php83-gd php83-iconv php83-phar composer nodejs php83-apache2 apache2 npm git python3 ca-certificates \
    curl ffmpeg chromium ttf-freefont font-noto-emoji  \
    && curl -Lo /usr/bin/yt-dlp https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp \
    && chmod a+rx /usr/bin/yt-dlp \
    && apk del curl

EXPOSE 80

RUN mkdir -p /var/www/laravel && \
    sed -i 's#^DocumentRoot ".*#DocumentRoot "/var/www/laravel/public"#g' /etc/apache2/httpd.conf && \
    sed -i 's#AllowOverride [Nn]one#AllowOverride All#' /etc/apache2/httpd.conf && \
    sed -i 's#Directory "/var/www/localhost/htdocs.*#Directory "/var/www/laravel/public" >#g' /etc/apache2/httpd.conf && \
    sed -i 's#\#LoadModule rewrite_module modules/mod_rewrite.so#LoadModule rewrite_module modules/mod_rewrite.so#' /etc/apache2/httpd.conf

WORKDIR /var/www/laravel

COPY . .

RUN echo "* * * * * cd /var/www/laravel && php artisan schedule:run >> /dev/null 2>&1" > /crontab.txt && \
    /usr/bin/crontab /crontab.txt && \
    echo -e "#!/bin/sh\n/usr/sbin/crond -b -l 8\n/usr/sbin/httpd -D FOREGROUND" > /entrypoint.sh && chmod +x /entrypoint.sh

RUN find . -type f -exec chmod 664 {} \; \
    && find . -type d -exec chmod 775 {} \; \
    && chown -R apache:apache * . \
    && composer install --no-dev -o \
    && npm install  \
    && chmod -R a+x node_modules  \
    && npm run production  \
    && php artisan optimize \
    && php artisan view:clear \
    && php artisan config:clear \
    && adduser -D chrome

ENTRYPOINT ["/entrypoint.sh"]
