.PHONY: start
start:
	php -S 127.0.0.1:8080 -t public/

.PHONY: test
test:
	vendor/bin/phpunit

.PHONY: setup
setup: composer-install

composer.phar:
	curl -sSfL -o composer-setup.php https://getcomposer.org/installer
	php composer-setup.php --filename=composer.phar
	rm composer-setup.php

.PHONY: composer-install
composer-install: composer.phar
	php composer.phar install
