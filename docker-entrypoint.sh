#!/bin/bash
set -e

PORT="${PORT:-7860}"

# Ensure required directories exist
mkdir -p database storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache

# Ensure SQLite file exists
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

# Run migrations
php artisan migrate --force

# Seed initial demo data if database has no users
USERS_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USERS_COUNT" = "0" ]; then
    echo "Seeding initial demo data for client demonstration..."
    php artisan db:seed --force
fi

# Clear previous cache to ensure fresh configuration
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize Laravel for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Laravel server on 0.0.0.0:$PORT
echo "Starting Natural Snacks Traceability on 0.0.0.0:${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
