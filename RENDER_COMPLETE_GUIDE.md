# JapranMCEH Backend - Complete Render Migration Guide

**Status:** ✅ Backend code ready for Render deployment

---

## What Changed

### Files Added
1. **`backend/render.yaml`** - Infrastructure as Code (defines web service + PostgreSQL database)
2. **`backend/Procfile`** - Startup command
3. **`backend/deploy.sh`** - Automated deployment script
4. **`RENDER_SETUP_MANUAL.md`** - UI-based setup guide
5. **`RENDER_SHELL_COMMANDS.md`** - Shell commands for manual deployment
6. **`RENDER_DEPLOY.md`** - Original deployment guide

### Files Removed
- `.github/workflows/deploy.yml` (old VPS SSH deployment)

### Files Updated
- **`backend/.env.example`** - Updated for Render environment variables

---

## Quick Start (3 Steps)

### Step 1: Push Code to GitHub
```bash
cd "path/to/web kasier vibecoder"
git push origin main
```

### Step 2: Create Resources on Render Dashboard
Go to https://dashboard.render.com

**A. Create PostgreSQL Database**
- Click **New +** → **PostgreSQL**
- Name: `japranmceh-db`
- Database: `japranmceh`
- User: `japranmceh_user`
- Region: Singapore
- Plan: Free
- Click **Create** → wait 2-3 min

**B. Create Web Service**
- Click **New +** → **Web Service**
- **Connect Repository** → `Fars-MD/pranxyzmc`
- Name: `japranmceh-backend`
- Runtime: PHP
- Region: Singapore
- Plan: Free
- Click **Deploy** → wait 5-10 min until "Live"

### Step 3: Run Setup in Render Shell
1. Web Service → **Shell** tab
2. Copy & paste from `RENDER_SHELL_COMMANDS.md`:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan config:cache
   php artisan route:cache
   ```

---

## Architecture

```
GitHub (Fars-MD/pranxyzmc)
    ↓ (push to main)
Render Web Service (japranmceh-backend)
    ├─ PHP 8.3 runtime
    ├─ Procfile: php -S 0.0.0.0:$PORT -t public
    └─ Auto-linked to PostgreSQL
        └─ Render Database (japranmceh-db)
            └─ Auto-provisioned with env vars
```

**Key Files on Render:**
- `render.yaml` - defines everything
- `Procfile` - starts the web server
- `composer.json` - dependencies (auto-installed)
- `database/migrations/` - run via `preDeployCommand`

---

## Environment Variables (Auto-Injected)

Render automatically sets these from `render.yaml`:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://japranmceh-backend.onrender.com
LOG_CHANNEL=stderr
DB_CONNECTION=pgsql
DB_HOST=<auto>
DB_PORT=<auto>
DB_DATABASE=<auto>
DB_USERNAME=<auto>
DB_PASSWORD=<auto>
SANCTUM_STATEFUL_DOMAINS=japranmceh.vercel.app
FRONTEND_URL=https://japranmceh.vercel.app
CORS_ALLOWED_ORIGINS=https://japranmceh.vercel.app
SESSION_DRIVER=file
CACHE_STORE=file
FILESYSTEM_DISK=local
```

No manual env setup needed.

---

## Deployment Flow (After Initial Setup)

1. **Make code changes** → `git commit && git push origin main`
2. **Render detects push** → Auto-runs build
3. **Build process:**
   - Clones repo
   - Reads `render.yaml`
   - Installs dependencies via `composer`
   - Runs `preDeployCommand: php artisan migrate --force`
   - Starts app via `Procfile`
4. **Service goes Live** → Available at `https://japranmceh-backend.onrender.com`

---

## Testing

### Verify API is Live
```bash
curl https://japranmceh-backend.onrender.com/api/products
# Returns: {"data":[],"current_page":1,...}
```

### Test Admin Login
```bash
curl -X POST https://japranmceh-backend.onrender.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@japranmceh.com","password":"admin123"}'
# Returns: {"token":"...","user":{...}}
```

### Check Logs
- Render Dashboard → `japranmceh-backend` → **Logs** tab

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Build fails | Check **Logs** tab → look for `composer install` errors |
| 502 Bad Gateway | Run in Shell: `php artisan config:cache` → restart service |
| Database connection error | Verify `DB_*` env vars in **Environment** tab |
| Admin login fails | Re-run in Shell: `php artisan db:seed --force` |
| Migrations didn't run | Manually in Shell: `php artisan migrate --force` |

---

## Update Frontend

After backend is Live:

1. Edit `frontend/.env`:
   ```
   VITE_API_URL=https://japranmceh-backend.onrender.com/api
   ```

2. Redeploy frontend to Vercel

3. Test from frontend:
   - Homepage loads
   - Products page fetches data
   - Admin login works

---

## Rollback

If deploy breaks:
1. Render Dashboard → `japranmceh-backend` → **Deploys** tab
2. Find previous working deploy
3. Click → **Redeploy**
4. Instant rollback, zero downtime

---

## Costs

**Free Tier:**
- 1 shared PostgreSQL DB
- 1 web service (auto-pauses after 15 min inactivity)
- $0/month

**Paid (Recommended for Production):**
- Dedicated PostgreSQL: $9-50+/month
- Web service: $7/month (always on)
- Total: ~$16-60/month

---

## Next Steps

- [ ] Push code to GitHub
- [ ] Create PostgreSQL database on Render
- [ ] Create web service, connect repo
- [ ] Wait for deployment to complete
- [ ] Run migrations in Shell
- [ ] Test API endpoints
- [ ] Update frontend `.env`
- [ ] Redeploy frontend
- [ ] Test full app flow

---

## Support Files

- `RENDER_SETUP_MANUAL.md` - Detailed dashboard walkthrough
- `RENDER_SHELL_COMMANDS.md` - All Shell commands with explanations
- `backend/render.yaml` - Infrastructure config
- `backend/Procfile` - Web server startup
- `backend/deploy.sh` - Automated deployment script (optional)

**You're all set.** Render will handle the rest automatically. 🚀
