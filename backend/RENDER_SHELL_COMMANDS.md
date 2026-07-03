# Render Shell - Manual Deployment Commands

**Once your web service is Live on Render, copy & paste these commands into the Shell tab:**

## 1. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

## 2. Generate App Key (if not already set)
```bash
php artisan key:generate
```

## 3. Run Migrations
```bash
php artisan migrate --force
```

## 4. Seed Database (Initial Data)
```bash
php artisan db:seed --force
```

## 5. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 6. Verify API is Working
```bash
curl http://localhost:9000/api/products
curl http://localhost:9000/api/settings
```

---

## Expected Output

After running all commands:
- ✓ Migrations table created
- ✓ All migration files executed
- ✓ Admin user created (admin@japranmceh.com / admin123)
- ✓ Settings & statistics seeded
- ✓ Config cached
- ✓ Service running on port 9000 (internal)

---

## Test from Outside (After Deploy Complete)

```bash
# List products
curl https://japranmceh-backend.onrender.com/api/products

# Get settings
curl https://japranmceh-backend.onrender.com/api/settings

# Admin login
curl -X POST https://japranmceh-backend.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@japranmceh.com","password":"admin123"}'
```

---

## If Something Goes Wrong

**Connection Error?**
```bash
# Check DB env vars
env | grep DB_
```

**Migration Failed?**
```bash
# Reset and retry
php artisan migrate:reset --force
php artisan migrate --force
```

**Key missing?**
```bash
php artisan key:generate
```

**Need to restart service?**
- Go to Web Service → **More** → **Restart**
