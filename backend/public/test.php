<?php
echo "server: OK\n";
echo "php_version: " . phpversion() . "\n";
echo "artisan_exists: " . (file_exists(__DIR__ . '/../artisan') ? 'yes' : 'no') . "\n";
echo "vendor_autoload_exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'yes' : 'no') . "\n";
echo "index_exists: " . (file_exists(__DIR__ . '/index.php') ? 'yes' : 'no') . "\n";
echo "bootstrap_app_exists: " . (file_exists(__DIR__ . '/../bootstrap/app.php') ? 'yes' : 'no') . "\n";
echo "env_exists: " . (file_exists(__DIR__ . '/../.env') ? 'yes' : 'no') . "\n";
echo "app_key: " . (getenv('APP_KEY') ? 'set' : 'not set') . "\n";
echo "db_host: " . (getenv('DB_HOST') ?: 'not set') . "\n";
echo "db_database: " . (getenv('DB_DATABASE') ?: 'not set') . "\n";
