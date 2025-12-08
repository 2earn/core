#!/bin/bash

php artisan migrate
php artisan db:seed
php artisan db:seed --class=OrderingSeeder
php artisan db:seed --class=OrdersTableSeeder
