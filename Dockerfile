FROM php:5.6-cli
COPY . /usr/src/strimoid
WORKDIR /usr/src/strimoid

RUN apt-get update -qq && apt-get install -y -qq git curl wget
RUN docker-php-ext-install mbstring mcrypt mongo

# Install composer dependencies
RUN wget http://getcomposer.org/composer.phar && mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer
RUN composer install

CMD [ "php", "-s", "public/index.php" ]