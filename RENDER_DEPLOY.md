# Render Deployment Guide

## Prerequisites
- Render account (free tier available)
- GitHub repo with backend code pushed

## Step 1: Create PostgreSQL Database on Render

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **New +** → **PostgreSQL**
3. Fill in:
   - **Name**: `japranmceh-db`
   - **Database**: `japranmceh`
   - **User**: `japranmceh_user`
   - **Region**: Pick closest to your users
   - **Plan**: Free (or paid for production)
4. Click **Create Database**
5. Wait for it to initialize (~2 min)
6. Note the **Internal Database URL** (you'll need this)

## Step 2: Connect Backend Repo to Render

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **New +** → **Web Service**
3. Click **Connect a repository** (link your GitHub account if needed)
4. Select `web-kasier-vibecoder` repo
5. Configure:
   - **Name**: `japranmceh-backend`
   - **Runtime**: `PHP`
   - **Build Command**: (leave empty — uses `render.yaml`)
   - **Start Command**: (leave empty — uses `Procfile`)
   - **Region**: Same as database
   - **Plan**: Free
6. Click **Advanced** and add environment variables (optional here, since `render.yaml` auto-injects):
   - Skip if using `render.yaml`
7. Click **Deploy**

## Step 3: Link Database to Web Service

Render auto-links services if names match in `render.yaml`. Verify:

1. Go to web service settings
2. **Environment** tab
3. Confirm `DB_*` variables are auto-populated from `japranmceh-db`

## Step 4: Run Migrations

Once deploy succeeds, migrations run automatically via `preDeployCommand`. If needed manually:

1. In Render dashboard → Web Service → **Shell**
2. Run:
   ```bash
   php artisan migrate --force
   php artisan db:seed
   ```

## Step 5: Update Frontend API URL

Update `frontend/.env`:
```
VITE_API_URL=https://japranmceh-backend.onrender.com/api
```

Then redeploy frontend.

## Step 6: Update CORS & Sanctum

In Render web service environment, verify:
- `SANCTUM_STATEFUL_DOMAINS`: `japranmceh.vercel.app`
- `CORS_ALLOWED_ORIGINS`: `https://japranmceh.vercel.app`

(Already set in `render.yaml`)

## Troubleshooting

### Build fails
- Check logs: Render Dashboard → Service → **Logs**
- Ensure `composer.json` dependencies install

### Database connection error
- Verify `DB_HOST`, `DB_PASSWORD` in Render env vars
- Test connection: `php artisan migrate --force` in Shell

### 502 Bad Gateway
- Check PHP-FPM/Apache logs: **Logs** tab
- Ensure `public/index.php` exists
- Restart service: **More** → **Restart**

### Admin login fails
- Run seeder: `php artisan db:seed` in Shell
- Default: `admin@japranmceh.com` / `admin123`

## Deployment Flow (After Initial Setup)

1. Push code to `main` branch
2. Render auto-detects via `render.yaml`
3. Builds & deploys automatically
4. (Optional) Trigger via GitHub Actions webhook

## Rollback

1. Render Dashboard → Service → **Deploys** tab
2. Click previous deploy → **Redeploy**

## Next Steps

- [ ] Create Render database
- [ ] Connect GitHub repo to Render web service
- [ ] Verify migrations run
- [ ] Test API endpoints: `https://japranmceh-backend.onrender.com/api/products`
- [ ] Update frontend `.env`
- [ ] Test admin login
