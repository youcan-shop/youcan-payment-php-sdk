.PHONY: all install test

all: install

install:
	composer install

test:
	composer test
