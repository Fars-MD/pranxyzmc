# Backend Migration to Render - Complete

## Files Created/Modified

### New Files
- `backend/render.yaml` - Render deployment config (PHP runtime, PostgreSQL, env vars, build/pre-deploy commands)
- `backend/Procfile` - Web process: `php -S 0.0.0.0:$PORT -t public`
- `RENDER_DEPLOY.md` - Step-by-step deployment guide
- `.github/workflows/render-deploy.yml` - Optional GitHub webhook for Render (not required, auto-deploy on push)

### Modified Files
- `backend/.env.example` - Updated for Render:
  - `APP_URL` → `https://japranmceh-backend.onrender.com`
  - Added `LOG_CHANNEL=stderr` (Render logging)
  - Added `SESSION_DRIVER=file`, `CACHE_STORE=file` (free tier compatible)
  - DB credentials blank (auto-injected by Render)

### Deleted Files
- `.github/workflows/deploy.yml` - Old VPS SSH deployment (no longer needed)

## What Changed

**Before (VPS):**
- Manual deployments via SSH to Ubuntu VPS
- Nginx + PHP-FPM
- Manual database management
- GitHub Actions SSH script

**After (Render):**
- Git-based auto-deploy (push to main → Render builds & deploys)
- Native PHP runtime on Render infrastructure
- PostgreSQL database managed by Render
- `render.yaml` defines everything (no manual config)
- Migrations run automatically on deploy

## Next Steps (Manual on Render Dashboard)

1. **Create PostgreSQL Database**
   - Go to [Render Dashboard](https://dashboard.render.com)
   - New → PostgreSQL
   - Name: `japranmceh-db`, Database: `japranmceh`, User: `japranmceh_user`, Plan: Free

2. **Connect GitHub Repo**
   - New → Web Service
   - Connect repo `Fars-MD/pranxyzmc`
   - Select GitHub account
   - Runtime: PHP
   - Region: (your choice)
   - Plan: Free

3. **Verify Auto-Link**
   - Render auto-links services if names match in `render.yaml`
   - Database env vars auto-injected
   - No manual environment setup needed

4. **Deploy**
   - Click Deploy
   - Build runs: `composer install --no-dev --optimize-autoloader`, cache busting
   - Pre-deploy: `php artisan migrate --force`
   - Service starts: `php -S 0.0.0.0:$PORT -t public`

5. **Seed Data (Optional)**
   - Render → Web Service → Shell tab
   - `php artisan db:seed`
   - Default admin: `admin@japranmceh.com` / `admin123`

6. **Update Frontend**
   - `frontend/.env`: `VITE_API_URL=https://japranmceh-backend.onrender.com/api`
   - Redeploy frontend to Vercel

## Key Features

✅ Zero-downtime deploys (git push)
✅ Auto migrations on each deploy
✅ Free PostgreSQL database (shared, free tier limits apply)
✅ Automatic environment variable injection
✅ Stdout logging (Render integration)
✅ File-based sessions/cache (free tier safe)
✅ No VPS/SSH maintenance needed

## Testing

After Render deployment:
```bash
curl https://japranmceh-backend.onrender.com/api/products
curl https://japranmceh-backend.onrender.com/api/settings
```

## Rollback

If issues arise:
- Render Dashboard → Deploys tab → Redeploy previous version
- No VPS needed; instant rollback

## Cost

- **Free Tier**: 1 shared PostgreSQL DB, 1 free web service (auto-spins down after 15 min inactivity)
- **Paid**: $7/month per service + database costs ($9-50+/month depending on size)

---

**Commit:** `085ea4b chore: migrate backend to Render`

**Status:** Ready to push to GitHub and connect via Render Dashboard.
