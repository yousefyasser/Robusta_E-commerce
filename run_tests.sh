#!/bin/zsh

echo "Running unit tests..."
docker compose exec web-server php artisan test

echo "Running static analysis..."
docker compose exec web-server vendor/bin/phpstan analyse --memory-limit=1G

echo "Seeding the database..."
docker compose exec web-server php artisan migrate:fresh --seed

echo "Running load tests..."
docker compose run k6
