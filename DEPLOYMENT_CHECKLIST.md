# ✅ Deployment Checklist - Supabase + Render

## Status Saat Ini

✅ **Completed:**
- Code pushed to GitHub (commit `1647721`)
- `render.yaml` configured dengan Supabase
- `.env.example` updated
- Documentation ready

❌ **Pending:**
- Deploy di Render Dashboard
- Verify migrations
- Test API

---

## LANGKAH 1: Deploy Backend di Render Dashboard

### 1.1 Login ke Render
- Go to: https://dashboard.render.com
- Login dengan account Anda

### 1.2 Create Web Service
1. Click **New +** (top-right)
2. Select **Web Service**
3. Click **Connect a repository**
4. Select: `Fars-MD/pranxyzmc`
5. Fill form:
   - **Name**: `japranmceh-backend`
   - **Runtime**: PHP
   - **Build Command**: (leave blank - uses render.yaml)
   - **Start Command**: (leave blank - uses Procfile)
   - **Region**: Singapore
   - **Plan**: Free
6. Click **Create Web Service** / **Deploy**

### 1.3 Wait for Build
- Status akan berubah: `Building` → `Deploying` → `Live`
- Tunggu ~5-10 menit
- Check **Logs** tab untuk progress

---

## LANGKAH 2: Verify di Render Shell

Setelah status **Live**, jalankan di **Shell** tab:

```bash
# Check migrations
php artisan migrate:status

# Seed database
php artisan db:seed --force

# Check users created
php artisan tinker
>>> User::count()
>>> exit
```

---

## LANGKAH 3: Test API Endpoints

```bash
# Test products (should return empty array initially)
curl https://japranmceh-backend.onrender.com/api/products

# Test settings
curl https://japranmceh-backend.onrender.com/api/settings

# Test login
curl -X POST https://japranmceh-backend.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@japranmceh.com","password":"admin123"}'
```

**Expected Response:**
```json
{
  "token": "xxx",
  "user": {
    "id": 1,
    "name": "Admin JapranMCEH",
    "email": "admin@japranmceh.com"
  }
}
```

---

## LANGKAH 4: Verify Database di Supabase

1. Go to: https://app.supabase.com
2. Select project: `kficrrrniytvubcfytkd`
3. Go to **SQL Editor** or **Tables**
4. Check tables exist:
   - `users` (1 record: admin user)
   - `products` (0 records initially)
   - `categories` (0 records initially)
   - `testimonials` (0 records initially)
   - `settings` (1 record)
   - `statistics` (1 record)
   - `migrations` (list of migrations)

---

## LANGKAH 5: Update Frontend

Edit: `frontend/.env`

Change from:
```
VITE_API_URL=http://localhost:8000/api
```

To:
```
VITE_API_URL=https://japranmceh-backend.onrender.com/api
```

Then push to GitHub → auto-deploy di Vercel

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Build fails | Check Render **Logs** → look for `composer install` errors |
| 502 Bad Gateway | Run in Shell: `php artisan config:cache` → restart service |
| Database error | Verify Supabase credentials in render.yaml match exactly |
| Admin login fails | Re-run in Shell: `php artisan db:seed --force` |
| API returns 404 | Service might still be deploying, wait 10 min |

---

## Quick Commands Summary

**In Render Shell:**
```bash
# View all available commands
php artisan

# Check DB connection
php artisan tinker
>>> DB::connection()->getPdo()
>>> exit

# View migrations
php artisan migrate:status

# Reset database (⚠️ deletes all data)
php artisan migrate:reset --force
php artisan migrate --force
php artisan db:seed --force
```

---

## What You Need to Do NOW

1. Go to https://dashboard.render.com
2. Follow **LANGKAH 1** above
3. Wait for deployment to complete
4. Run commands from **LANGKAH 2**
5. Test endpoints from **LANGKAH 3**
6. Report back results

**Status:** Waiting for Render deployment to complete
