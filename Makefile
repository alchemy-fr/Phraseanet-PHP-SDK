VALIDATE = $(shell composer validate 2>&1 | grep -q "lock file" && echo "composer.status")

.PHONY: test clean-deps phpunit phpcs composer.status

test: deps phpunit phpcs

phpunit:
	vendor/bin/phpunit --coverage-text --coverage-clover=tests/output/coverage.clover

phpcs:
	vendor/bin/phpcs --standard=psr1,psr2 --ignore=src/Bundle/Resources/* src

fixcs:
	vendor/bin/phpcbf --standard=psr1,psr2 --ignore=src/Bundle/Resources/* src 

deps: composer.lock vendor/composer/installed.json

vendor/composer/installed.json: composer.json
	composer install

composer.lock: $(VALIDATE)
	composer update

composer.status:
	rm -f composer.lock

ocular:
	[ ! -f ocular.phar ] && wget https://scrutinizer-ci.com/ocular.phar

ifdef OCULAR_TOKEN
scrutinizer: ocular
	@php ocular.phar code-coverage:upload --format=php-clover tests/output/coverage.clover --access-token=$(OCULAR_TOKEN);
else
scrutinizer: ocular
	php ocular.phar code-coverage:upload --format=php-clover tests/output/coverage.clover;
endif

clean-deps:
	rm -rf vendor/
