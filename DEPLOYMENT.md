# Deployment Guide - Render Platform

## 🚀 Quick Deploy to Render

### Option 1: Using Render Dashboard (Recommended)

1. **Connect Repository**
   - Go to [Render Dashboard](https://dashboard.render.com)
   - Click "New +" → "Web Service"
   - Connect your Git repository

2. **Configure Service**
   - **Name**: `labour-chowk` (or your preferred name)
   - **Region**: Choose closest to your users
   - **Branch**: `main` (or your production branch)
   - **Root Directory**: Leave empty (root of repo)
   - **Runtime**: `Docker`
   - **Dockerfile Path**: `Dockerfile` (should auto-detect)

3. **Environment Variables**
   Add these in Render dashboard:
   ```
   APP_NAME=LabourChowk
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app-name.onrender.com
   APP_KEY=base64:... (generate with: php artisan key:generate --show)

   DB_CONNECTION=mysql
   DB_HOST=your-db-host
   DB_PORT=3306
   DB_DATABASE=your-db-name
   DB_USERNAME=your-db-user
   DB_PASSWORD=your-db-password

   LOG_CHANNEL=stderr
   LOG_LEVEL=error
   ```

4. **Database Setup**
   - Create a PostgreSQL or MySQL database on Render
   - Copy connection details to environment variables above
   - Run migrations: Add a build command or run manually

5. **Build & Deploy**
   - Render will automatically build using Dockerfile
   - First deploy may take 5-10 minutes
   - Subsequent deploys are faster

### Option 2: Using render.yaml (Auto-config)

1. **Push render.yaml** (already in repo)
2. **Connect Repository** in Render dashboard
3. **Render will auto-detect** render.yaml and configure service
4. **Add environment variables** manually (as above)

---

## 📋 Pre-Deployment Checklist

### 1. Generate Application Key
```bash
php artisan key:generate --show
```
Copy the output and add as `APP_KEY` in Render environment variables.

### 2. Database Setup
- Create database on Render (PostgreSQL recommended for free tier)
- Note connection details
- Add to environment variables

### 3. Update APP_URL
- Set `APP_URL` to your Render service URL
- Format: `https://your-app-name.onrender.com`

### 4. Storage Link
The Dockerfile automatically runs `php artisan storage:link` on startup.

### 5. Run Migrations
After first deploy, you can:
- Use Render Shell to run: `php artisan migrate --seed`
- Or add to Dockerfile entrypoint (not recommended for production)

---

## 🔧 Post-Deployment Steps

### 1. Run Migrations
```bash
# Via Render Shell or SSH
php artisan migrate --seed
```

### 2. Create Admin User
If not seeded, create admin:
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('secure-password'),
    'is_admin' => true,
]);
```

### 3. Verify Storage Link
```bash
php artisan storage:link
```

### 4. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🐳 Dockerfile Details

The Dockerfile uses a **multi-stage build**:

1. **Base Stage**: Installs dependencies, builds assets
2. **Production Stage**: Minimal runtime image

**Key Features**:
- PHP 8.3 FPM with Alpine (small image)
- Composer dependencies (production only)
- Node.js for building frontend assets
- Proper permissions for Laravel
- Optimized autoloader
- Cached config/routes/views

---

## 🔐 Security Considerations

1. **Environment Variables**
   - Never commit `.env` file
   - Use Render's environment variable management
   - Rotate `APP_KEY` if exposed

2. **Database**
   - Use strong database passwords
   - Enable SSL if available

3. **Application**
   - `APP_DEBUG=false` in production
   - Change default admin credentials
   - Review `APP_URL` is correct

---

## 📊 Monitoring

### Health Check
- Render automatically checks `/` endpoint
- Ensure route returns 200 status

### Logs
- View logs in Render dashboard
- `LOG_CHANNEL=stderr` sends logs to Render's log viewer

### Performance
- Free tier has limitations (spins down after inactivity)
- Consider upgrading for production use

---

## 🔄 Updating Application

1. **Push to Git**
   ```bash
   git push origin main
   ```

2. **Auto-Deploy**
   - Render automatically detects changes
   - Rebuilds Docker image
   - Deploys new version

3. **Manual Deploy**
   - Go to Render dashboard
   - Click "Manual Deploy" if needed

---

## 🐛 Troubleshooting

### Build Fails
- Check Dockerfile syntax
- Verify all dependencies in composer.json/package.json
- Check Render build logs

### Application Won't Start
- Verify environment variables are set
- Check `APP_KEY` is generated
- Review application logs in Render dashboard

### Database Connection Issues
- Verify database credentials
- Check database is accessible from Render
- Ensure database is created and running

### Storage Issues
- Verify `storage:link` ran successfully
- Check file permissions
- Review storage directory exists

### 500 Errors
- Check `APP_DEBUG=true` temporarily (for debugging only)
- Review logs in Render dashboard
- Verify database migrations ran

---

## 💰 Free Tier Limitations

- **Spins down** after 15 minutes of inactivity
- **Cold start** takes ~30-60 seconds
- **512MB RAM** limit
- **100GB bandwidth** per month
- **PostgreSQL** database (free tier available)

**For Production**: Consider upgrading to paid plan for:
- Always-on service
- More resources
- Better performance

---

## 📝 Notes

- **First Deploy**: Takes 5-10 minutes (building Docker image)
- **Subsequent Deploys**: Faster (cached layers)
- **Database**: Run migrations after first deploy
- **Storage**: Photos stored in `storage/app/public/` (persists across deploys)
- **Cron Jobs**: Not available on free tier (use external service if needed)

---

## 🔗 Useful Links

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

---

**Need Help?** Check Render's logs and Laravel's error logs for detailed error messages.

