#!/bin/sh
set -e

# Function to wait for a service
wait_for_service() {
    host="$1"
    port="$2"
    service_name="$3"
    max_attempts=30

    echo "Waiting for $service_name..."
    for i in $(seq 1 $max_attempts); do
        if nc -z "$host" "$port" > /dev/null 2>&1; then
            echo "$service_name is ready!"
            return 0
        fi
        echo "Attempt $i/$max_attempts: $service_name is not ready. Waiting..."
        sleep 2
    done

    echo "$service_name is not available after $max_attempts attempts"
    return 1
}

echo "Starting entrypoint script..."

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for Elasticsearch
wait_for_service "$ELASTICSEARCH_HOST" "$ELASTICSEARCH_PORT" "Elasticsearch"

# Wait for MySQL
wait_for_service "$DB_HOST" "3306" "MySQL"

# Switch to www-data user for Laravel commands
su www-data -s /bin/sh -c "
    cd /var/www/html && \
    # Generate application key if not set
    if [ -z \"$APP_KEY\" ]; then
        php artisan key:generate --no-interaction --force
    fi && \
    # Clear config cache
    php artisan config:clear && \
    # Run migrations
    php artisan migrate --force && \
    # Create Elasticsearch index
    php artisan elasticsearch:setup
"

# Start PHP-FPM
exec "$@" 