#!/bin/bash

# Parse DATABASE_URL jika ada
if [ -n "$DATABASE_URL" ]; then
    # Extract database credentials dari DATABASE_URL
    # Format: mysql://user:pass@host:port/dbname
    
    export DB_CONNECTION=mysql
    
    # Parse URL menggunakan regex sederhana
    DB_USER=$(echo $DATABASE_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    DB_PASS=$(echo $DATABASE_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')
    DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    DB_NAME=$(echo $DATABASE_URL | sed -n 's/.*\/\([^?]*\).*/\1/p')
    
    export DB_HOST=$DB_HOST
    export DB_PORT=$DB_PORT
    export DB_DATABASE=$DB_NAME
    export DB_USERNAME=$DB_USER
    export DB_PASSWORD=$DB_PASS
fi

# Jalankan migration dan seeder
php artisan migrate --force
php artisan db:seed --force

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT