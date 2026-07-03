# Railway Deployment Guide

## Architecture

```
GitHub (Fars-MD/pranxyzmc)
    ↓
Railway Web Service (japranmceh-backend)
    ├─ PHP 8.3 (Nixpacks)
    ├─ nixpacks.toml config
    └─ Auto-linked to Supabase PostgreSQL
```

---

## Step 1: Create Railway Project

1. Go to https://railway.app
2. Click **New Project**
3. Select **Deploy from GitHub repo**
4. Authorize & select: `Fars-MD/pranxyzmc`
5. Click **Deploy Now**

---

## Step 2: Configure Service

1. Click on the service card
2. Go to **Settings** tab
3. Configure:

**Build:**
- Builder: **Nixpacks** (auto-detect)
- Root Directory: `backend`

**Deploy:**
- Start Command: `php artisan serve --host 0.0.0.0 --port $PORT`
- Healthcheck Path: `/api/products`
- Restart Policy: **ON_FAILURE**

**Environment Variables** (add all):
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:QV9K3mH7gK2xR5jN8pT4wY6zA1bC9dE0fG2hI4jK6lM=
APP_URL=https://japranmceh-backend.up.railway.app
LOG_CHANNEL=stderr
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.kficrrrniytvubcfytkd
DB_PASSWORD=Zhafar1334455
SANCTUM_STATEFUL_DOMAINS=japranmceh.vercel.app
FRONTEND_URL=https://japranmceh.vercel.app
CORS_ALLOWED_ORIGINS=https://japranmceh.vercel.app
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
PHP_VERSION=8.3
```

---

## Step 3: Generate App Key

After first deploy, go to service → **Shell** tab:

```bash
php artisan key:generate
```

Or copy the key above and paste as env var.

---

## Step 4: Run Migrations

In Railway Shell:

```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## Step 5: Get Public URL

1. Go to **Settings** → **Networking**
2. Click **Generate Domain**
3. Copy URL (e.g., `japranmceh-backend.up.railway.app`)

---

## Step 6: Test API

```bash
curl https://japranmceh-backend.up.railway.app/api/products
curl https://japranmceh-backend.up.railway.app/api/settings

# Login test
curl -X POST https://japranmceh-backend.up.railway.app/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@japranmceh.com","password":"admin123"}'
```

---

## Step 7: Update Frontend

Edit `frontend/.env`:
```
VITE_API_URL=https://japranmceh-backend.up.railway.app/api
```

Push to GitHub → auto-deploy to Vercel.

---

## Custom Domain (Optional)

1. Railway → Service → **Settings** → **Networking**
2. Click **Custom Domain**
3. Add: `api.yourdomain.com`
4. Add CNAME in your DNS:
   ```
   CNAME api.yourdomain.com → <railway-provided>.up.railway.app
   ```

---

## Cost

- **Hobby Plan**: $5/month (includes $5 usage)
- **Free Trial**: $5 credit
- **PostgreSQL**: Use Supabase (free tier)

Total: ~$5/month (if exceeds free credits)

---

## Troubleshooting

### Build Failed
- Check **Logs** tab
- Verify `nixpacks.toml` exists
- Try rebuild: **Deployments** → **Redeploy**

### Database Connection Error
- Test in Shell: `php artisan tinker` → `DB::connection()->getPdo()`
- Verify Supabase credentials match exactly

### 502 Bad Gateway
- Check start command: `php artisan serve --host 0.0.0.0 --port $PORT`
- Verify `$PORT` is used (Railway auto-sets this)

### Migrations Not Running
- Run manually in Shell: `php artisan migrate --force`
- Check DB connection first

---

## Files Used

- `backend/nixpacks.toml` - Build config (PHP 8.3)
- `backend/.env.example` - Environment template
- `backend/railway.yaml` - Railway infrastructure config (optional)

Railway will auto-detect Laravel via Nixpacks.
