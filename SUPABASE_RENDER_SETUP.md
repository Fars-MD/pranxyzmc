# Supabase + Render Deployment Guide

## Architecture

```
Supabase PostgreSQL Database
    ↑
    |
Render Web Service (japranmceh-backend)
    ↑
    |
GitHub (Fars-MD/pranxyzmc)
    ↑
    |
Frontend (Vercel)
```

---

## Supabase Credentials

**Project URL:** `https://kficrrrniytvubcfytkd.supabase.co`

**Database Connection:**
- Host: `aws-0-ap-southeast-1.pooler.supabase.com`
- Port: `6543`
- Database: `postgres`
- User: `postgres.kficrrrniytvubcfytkd`
- Password: `Zhafar1334455`

**Anon Key:** `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtmaWNycnJuaXl0dnViY2Z5dGtkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODMwNjI1OTksImV4cCI6MjA5ODYzODU5OX0.AEs4qqpl48F71oWP1DiiZyk8siAJIsQVzLF-3puR8gs`

---

## Step 1: Deploy Backend to Render

### 1a. Create Web Service
1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **New +** → **Web Service**
3. Connect repo: `Fars-MD/pranxyzmc`
4. Configure:
   - **Name**: `japranmceh-backend`
   - **Runtime**: PHP
   - **Region**: Singapore
   - **Plan**: Free
5. Click **Deploy**

### 1b. Verify Environment Variables
After deployment starts, check **Environment** tab:
- All `DB_*` variables should be set from `render.yaml`
- Verify Supabase connection string is correct

### 1c. Wait for Build Complete
- Build runs: `composer install --no-dev`
- Pre-deploy: `php artisan migrate --force` (creates tables in Supabase)
- Service goes Live (~5-10 min)

---

## Step 2: Verify Database Migration

Once service is **Live**:

1. Go to Web Service → **Shell** tab
2. Run migrations (should auto-run, but verify):
   ```bash
   php artisan migrate --status
   ```
   Should show all migrations as ✓

3. Seed database:
   ```bash
   php artisan db:seed --force
   ```

4. Verify data:
   ```bash
   php artisan tinker
   >>> User::count()
   1
   >>> Product::count()
   0
   ```

---

## Step 3: Test API

```bash
# Test products endpoint
curl https://japranmceh-backend.onrender.com/api/products

# Test settings
curl https://japranmceh-backend.onrender.com/api/settings

# Test admin login
curl -X POST https://japranmceh-backend.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@japranmceh.com","password":"admin123"}'
```

---

## Step 4: Check Data in Supabase

1. Go to [Supabase Console](https://app.supabase.com)
2. Select project: `kficrrrniytvubcfytkd`
3. Go to **SQL Editor** or **Tables**
4. Verify tables exist:
   - `users` (1 admin record)
   - `products`
   - `categories`
   - `testimonials`
   - `settings`
   - `statistics`

---

## Step 5: Update Frontend

Edit `frontend/.env`:
```
VITE_API_URL=https://japranmceh-backend.onrender.com/api
```

Then deploy frontend to Vercel.

---

## Troubleshooting

### Migration Failed
**Error in Shell:** `SQLSTATE[HY000]: General error: 7 SSL error`

**Solution:** Supabase requires SSL. In `config/database.php`, ensure:
```php
'pgsql' => [
    ...
    'sslmode' => 'require',
]
```
✓ Already configured in codebase.

### Connection Timeout
**Error:** `SQLSTATE[HY000]: General error: 1 could not connect`

**Solution:** 
- Check firewall allows Supabase IP range
- Verify credentials in render.yaml
- Test locally: `psql postgresql://postgres.kficrrrniytvubcfytkd:Zhafar1334455@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres`

### SSL Certificate Error
**Solution:** Add to `backend/config/database.php`:
```php
'sslmode' => 'require',
'sslcert' => null,
'sslkey' => null,
'sslrootcert' => null,
```

---

## Security Notes

⚠️ **Database credentials are in `render.yaml`**
- These are stored encrypted in Render
- Only visible to you + Render admins
- Change password in Supabase → rotate in Render

🔒 **Don't commit credentials to GitHub**
- Already in `.gitignore` (render.yaml not tracked)
- Use environment variables only

---

## Cost

- **Supabase PostgreSQL**: Free tier (500 MB) or $25/month (Paid)
- **Render PHP Web Service**: Free tier (auto-pauses) or $7/month (Paid)
- **Total**: ~$0-32/month

---

## Next Steps

- [ ] Deploy to Render (follow Step 1)
- [ ] Verify migrations run (Step 2)
- [ ] Test API endpoints (Step 3)
- [ ] Check Supabase tables (Step 4)
- [ ] Update + deploy frontend (Step 5)
