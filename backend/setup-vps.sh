#!/bin/bash
set -e

echo "=== JapranMCEH Backend - VPS Setup ==="

# --- Update system ---
sudo apt update && sudo apt upgrade -y

# --- Install Nginx ---
sudo apt install -y nginx
sudo systemctl enable nginx

# --- Install PHP 8.3 ---
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-common \
  php8.3-mysql php8.3-pgsql php8.3-mbstring php8.3-xml \
  php8.3-curl php8.3-gd php8.3-bcmath php8.3-zip \
  php8.3-tokenizer php83-json php8.3-fileinfo php8.3-ctype \
  php8.3-bz2 php8.3-intl php8.3-opcache

sudo systemctl enable php8.3-fpm

# --- Install MySQL ---
sudo apt install -y mysql-server
sudo systemctl enable mysql

# --- Install Composer ---
if ! command -v composer &>/dev/null; then
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  php -r "unlink('composer-setup.php');"
fi

# --- Install Certbot ---
sudo apt install -y certbot python3-certbot-nginx

# --- Create project directory ---
sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www

# --- Clone repository ---
git clone https://github.com/porposalke1/japranmceh-backend.git /var/www/japranmceh-backend

# --- Setup .env ---
cd /var/www/japranmceh-backend/backend
cp .env.example .env
php artisan key:generate

# --- Install dependencies ---
composer install --no-dev --optimize-autoloader

# --- Setup storage ---
sudo chmod -R 775 storage bootstrap/cache
php artisan storage:link

# --- Configure Nginx ---
sudo cp nginx.conf /etc/nginx/sites-available/japranmceh-backend
sudo ln -sf /etc/nginx/sites-available/japranmceh-backend /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

# --- Setup SSL ---
echo ""
echo "=== SSL Setup ==="
echo "Run: sudo certbot --nginx -d api.japranmceh.com"
echo ""

# --- Run migration ---
echo ""
echo "=== Database Migration ==="
echo "1. sudo mysql -e \"CREATE DATABASE japranmceh;\""
echo "2. Update .env with DB credentials"
echo "3. php artisan migrate --force"
echo ""

echo "=== Setup Complete ==="
echo "Next steps:"
echo "  1. Edit /var/www/japranmceh-backend/.env with your DB credentials"
echo "  2. Run php artisan migrate --force"
echo "  3. Run sudo certbot --nginx -d api.japranmceh.com"
echo "  4. Test: curl http://api.japranmceh.com/api/products"
