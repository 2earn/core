#!/bin/bash

php artisan migrate
php artisan db:seed

# for local envs
php artisan db:seed --class=OrderingSeeder
php artisan db:seed --class=OrdersTableSeeder

php artisan translate:sync-all
php artisan translate:update-model
