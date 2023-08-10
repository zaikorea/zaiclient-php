#!/bin/bash
PHP_VERSIONS=('7.0.33' '7.1.33' '7.2.34' '7.3.33' '7.4.30')
for version in "${PHP_VERSIONS[@]}"
do
    echo "testing php $version"
    source ~/.phpbrew/bashrc
    phpbrew switch ${version}
    php -v
    php ../composers/v7.x/composer.phar install -q
    ./vendor/bin/phpunit
    rm -rf vendor
    rm composer.lock
done

PHP_VERSIONS=('8.0.21' '8.1.3')
for version in "${PHP_VERSIONS[@]}"
do
    echo "testing php $version"
    source ~/.phpbrew/bashrc
    phpbrew switch ${version}
    php -v
    php ../composers/v8.x/composer.phar install -q
    ./vendor/bin/phpunit
    rm -rf vendor
    rm composer.lock
done
