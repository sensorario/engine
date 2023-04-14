#!/bin/bash
composer install
./bin/phpunit --stop-on-failure --color --display-deprecations
