#!/usr/bin/env bash
set -e

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting server..."
# Default to Apache, but allow override or fallback
if [ -n "$StartCommand" ]; then
    $StartCommand
else
    heroku-php-apache2 public/
fi
