#!/bin/bash
set -e

echo "=== JapranMCEH Backend - Render Deployment Script ==="
echo ""

# Check environment
echo "Checking environment..."
echo "PHP Version: $(php --version | head -1)"
echo "Composer: $(composer --version)"
echo ""

# Check .env
if [ ! -f .env ]; then
  echo "⚠ .env not found. Creating from .env.example..."
  cp .env.example .env
  php artisan key:generate
fi

echo "✓ .env exists"
echo ""

# Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader
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

echo "=== Deployment Complete ==="
echo ""
echo "API Ready at: https://japranmceh-backend.onrender.com/api"
echo ""
echo "Test endpoints:"
echo "  curl https://japranmceh-backend.onrender.com/api/products"
echo "  curl https://japranmceh-backend.onrender.com/api/settings"
echo ""
echo "Admin Login:"
echo "  Email: admin@japranmceh.com"
echo "  Password: admin123"
