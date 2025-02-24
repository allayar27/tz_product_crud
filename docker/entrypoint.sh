#!/bin/bash
set -e

echo "Starting entrypoint script..."

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
    case "$APP_ENV" in
    "local")
        echo "Copying .env.example ... "
        cp .env.example .env
    ;;
    "prod")
        echo "Copying .env.prod ... "
        cp .env.prod .env
    ;;
    esac
else
    echo "env file exists."
fi


php artisan key:generate
php artisan clear
php artisan optimize:clear
php artisan migrate
php artisan jwt:secret
php artisan storage:link 

echo "Fixing file permissions..."

chown -R www-data:www-data .
chown -R www-data:www-data /var/www/app/storage
chown -R www-data:www-data /var/www/app/storage/logs
chown -R www-data:www-data /var/www/app/storage/framework
chown -R www-data:www-data /var/www/app/storage/framework/sessions
chown -R www-data:www-data /var/www/app/bootstrap
chown -R www-data:www-data /var/www/app/bootstrap/cache

# # Set correct permission.
chmod -R 775 /var/www/app/storage
chmod -R 775 /var/www/app/storage/logs
chmod -R 775 /var/www/app/storage/framework
chmod -R 775 /var/www/app/storage/framework/sessions
chmod -R 775 /var/www/app/bootstrap
chmod -R 775 /var/www/app/bootstrap/cache

echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
