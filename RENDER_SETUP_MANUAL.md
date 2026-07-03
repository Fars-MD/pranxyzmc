# Manual Render Setup Guide (Token Pre-Authenticated)

## Your Render Token
```
rnd_gKxNbNKfKbfIWWoFBw4Dv0IN3J5R
```

---

## Step 1: Create PostgreSQL Database

1. Open [Render Dashboard](https://dashboard.render.com)
   - Token should auto-authenticate you
2. Click **New +** (top-right)
3. Select **PostgreSQL**
4. Fill in form:
   - **Name**: `japranmceh-db`
   - **Database**: `japranmceh`
   - **User**: `japranmceh_user`
   - **Region**: Singapore (or closest to you)
   - **Plan**: Free
5. Click **Create Database**
6. ⏳ Wait 2-3 minutes for provisioning
7. Once ready, go to **Info** tab and copy:
   - **Host** (Internal: `japranmceh-db.c...render.internal`)
   - **Port** (5432)
   - **Database** (japranmceh)
   - **User** (japranmceh_user)
   - **Password** (shown in credentials)

---

## Step 2: Connect GitHub Repo

1. Dashboard → **New +** → **Web Service**
2. Click **Connect a repository**
3. Authorize GitHub (if not already)
4. Select: `Fars-MD/pranxyzmc`
5. Configure:
   - **Name**: `japranmceh-backend`
   - **Runtime**: PHP
   - **Build Command**: Leave blank (auto-uses `render.yaml`)
   - **Start Command**: Leave blank (auto-uses `Procfile`)
   - **Region**: Singapore (match DB)
   - **Plan**: Free
6. Click **Advanced** → Environment
   - Should auto-link to `japranmceh-db`
   - Verify `DB_*` vars are present
7. Click **Deploy**
8. ⏳ Wait 5-10 minutes for first build

---

## Step 3: Run Migrations

Once deployed (status = "Live"):

1. Web Service → **Shell** tab
2. Paste & run:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

Expected output:
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
...
Seeding database with seeders...
Database seeding completed successfully.
```

---

## Step 4: Test API

1. Open browser/terminal:
   ```bash
   curl https://japranmceh-backend.onrender.com/api/products
   ```
   Should return: `{"data":[],"current_page":1,...}`

2. Test settings:
   ```bash
   curl https://japranmceh-backend.onrender.com/api/settings
   ```

3. Test login (admin@japranmceh.com / admin123):
   ```bash
   curl -X POST https://japranmceh-backend.onrender.com/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@japranmceh.com","password":"admin123"}'
   ```
   Should return token + user data.

---

## Step 5: Update Frontend

Edit `frontend/.env`:
```
VITE_API_URL=https://japranmceh-backend.onrender.com/api
```

Redeploy frontend (Vercel).

---

## Troubleshooting

### Database not linking
- Web Service → **Environment** tab
- Manually add:
  ```
  DB_HOST=japranmceh-db.c...render.internal
  DB_PORT=5432
  DB_DATABASE=japranmceh
  DB_USERNAME=japranmceh_user
  DB_PASSWORD=<from DB Info>
  ```

### Build fails
- Web Service → **Logs** tab
- Check for `composer install` errors
- Run manually in Shell: `composer install --no-dev`

### 502 Bad Gateway
- Check PHP errors: **Logs** tab
- Run in Shell: `php artisan config:cache && php artisan route:cache`

### Admin login fails
- Re-run seeder in Shell: `php artisan db:seed --force`

---

## Next Steps

- [ ] Create PostgreSQL database
- [ ] Connect GitHub repo to web service
- [ ] Deploy web service
- [ ] Run migrations in Shell
- [ ] Test API endpoints
- [ ] Update frontend .env
- [ ] Redeploy frontend
