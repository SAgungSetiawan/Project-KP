web: vendor/bin/heroku-php-apache2 public/
release: 
  php -r "copy('.env.example', '.env');" && 
  php artisan key:generate --force && 
  php artisan config:cache && 
  php artisan migrate --force