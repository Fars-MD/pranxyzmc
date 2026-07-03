#!/bin/bash
set -e

echo "=== JapranMCEH Backend - Railway Deployment Setup ==="
echo ""

# Check environment
echo "Checking environment..."
echo "PHP Version: $(php --version | head -1)"
echo "Composer: $(composer --version)"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
  echo "Creating .env from .env.example..."
  cp .env.example .env
  php artisan key:generate
fi

echo "✓ .env exists"
echo ""

# Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
echo "✓ Dependencies installed"
echo ""

# Run migrations
echo "Running database migrations..."
php artisan migrate --force
echo "✓ Migrations complete"
echo ""

# Seed database
echo "Seeding database..."
php artisan db:seed --force
echo "✓ Database seeded"
echo ""

# Cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo "✓ Caching complete"
echo ""

echo "=== Deployment Setup Complete ==="
echo ""
echo "API should be available at your Railway domain"
echo "Test: curl https://japranmceh-backend.up.railway.app/api/products"
