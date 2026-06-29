#!/bin/sh
set -e

echo "Waiting for database connection..."
until php -r "
try {
    \$pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
"; do
  echo "Database not ready, retrying in 2s..."
  sleep 2
done
echo "Database is up."

if [ -z "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

( while true; do php artisan schedule:run --no-interaction >> /dev/null 2>&1; sleep 60; done ) &

exec "$@"