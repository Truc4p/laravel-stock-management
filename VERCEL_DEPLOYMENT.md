# Vercel Deployment Guide

## Prerequisites
- Vercel account (sign up at https://vercel.com)
- Vercel CLI installed: `npm i -g vercel`
- Git repository (GitHub, GitLab, or Bitbucket)

## Deployment Steps

### 1. Install Vercel CLI (if not already installed)
```bash
npm i -g vercel
```

### 2. Login to Vercel
```bash
vercel login
```

### 3. Deploy the Project
From your project directory, run:
```bash
vercel
```

Follow the prompts:
- Set up and deploy? **Y**
- Which scope? Choose your account
- Link to existing project? **N**
- What's your project's name? **warehouse-management-system** (or your preferred name)
- In which directory is your code located? **./**
- Want to override the settings? **N**

### 4. Set Environment Variables on Vercel

After initial deployment, set these environment variables in the Vercel dashboard:

**Required Variables:**
```
APP_NAME="Warehouse Management System"
APP_KEY=base64:521efZ9fWpPKBIj4vV4pHfg1T+ndi0ZSxXFI0HjmuaM=
APP_URL=https://your-project.vercel.app
APP_ENV=production
APP_DEBUG=false

DB_CONNECTION=sqlite
DB_DATABASE=/tmp/database.sqlite

SESSION_DRIVER=cookie
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

**How to add environment variables:**
1. Go to your project dashboard on Vercel
2. Click **Settings** → **Environment Variables**
3. Add each variable one by one
4. Click **Save**

### 5. Important Notes for SQLite on Vercel

⚠️ **SQLite Limitation**: Vercel's serverless functions have a read-only filesystem (except `/tmp`). 

**Solutions:**
1. **Use PlanetScale or MySQL** (Recommended for production):
   ```
   DB_CONNECTION=mysql
   DB_HOST=your-database-host
   DB_PORT=3306
   DB_DATABASE=your-database-name
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   ```

2. **OR use Turso (SQLite in the cloud)**:
   - Sign up at https://turso.tech
   - Create a database
   - Update environment variables

### 6. Build Assets Before Deployment

Make sure to build your frontend assets:
```bash
npm install
npm run build
```

Commit the `public/build` directory to your repository.

### 7. Redeploy with Environment Variables
After setting environment variables:
```bash
vercel --prod
```

## Accessing Your Deployed App

Your app will be available at: `https://your-project-name.vercel.app`

## Troubleshooting

### Issue: 500 Error
- Check Vercel logs: `vercel logs [deployment-url]`
- Verify all environment variables are set
- Ensure APP_KEY is set correctly

### Issue: Database Connection Failed
- Remember: SQLite doesn't work well on Vercel
- Use a cloud database (MySQL, PostgreSQL, or PlanetScale)

### Issue: Assets Not Loading
- Make sure `npm run build` was executed
- Verify `public/build` directory exists and is committed
- Check the routes in vercel.json

## Alternative: Deploy Using Git Integration

1. Push your code to GitHub/GitLab/Bitbucket
2. Go to https://vercel.com/new
3. Import your repository
4. Vercel will auto-detect settings
5. Add environment variables
6. Click "Deploy"

## Production Database Setup (Recommended)

For production, use **PlanetScale** (free MySQL database):

1. Sign up at https://planetscale.com
2. Create a new database
3. Get connection details
4. Update Vercel environment variables:
   ```
   DB_CONNECTION=mysql
   DB_HOST=your-host.psdb.cloud
   DB_PORT=3306
   DB_DATABASE=your-database-name
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt
   ```
5. Run migrations (you may need to do this locally first)

## Continuous Deployment

Once set up with Git, every push to your main branch will automatically deploy to Vercel!
