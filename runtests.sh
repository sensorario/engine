#!/bin/bash
composer install
./vendor/bin/phpunit --stop-on-failure --color --display-deprecations
