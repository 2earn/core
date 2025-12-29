#!/bin/bash

php artisan migrate
php artisan db:seed

# For local envs
php artisan db:seed --class=OrderingSeeder
php artisan db:seed --class=OrdersTableSeeder

php artisan translate:sync-all
php artisan translate:update-model
