#!/bin/zsh

echo "Running unit tests..."
php artisan test

echo "Running static analysis..."
vendor/bin/phpstan analyse --memory-limit=1G

echo "Seeding the database..."
php artisan migrate:fresh --seed

echo "Running load tests..."
docker run --network="host" -i grafana/k6 run - <shoppingScenarioTesting.js
