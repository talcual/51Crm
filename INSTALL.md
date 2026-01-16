# Installation Guide - 51CRM

This guide will walk you through the complete installation and setup process for the 51CRM application.

## Prerequisites

Before you begin, ensure your system meets the following requirements:

- **PHP**: Version 8.1 or higher
- **Composer**: Latest version
- **Node.js**: Version 16 or higher
- **NPM**: Version 8 or higher
- **Database**: MySQL 5.7+ or PostgreSQL 10+
- **Web Server**: Apache or Nginx (for production)

### Verifying Prerequisites

```bash
# Check PHP version
php -v

# Check Composer version
composer --version

# Check Node.js version
node -v

# Check NPM version
npm -v
```

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/talcual/51Crm.git
cd 51Crm
```

### 2. Install PHP Dependencies

```bash
composer install
```

This will install all Laravel and PHP packages defined in `composer.json`.

### 3. Install JavaScript Dependencies

```bash
npm install
```

This will install all Node.js packages needed for the frontend.

### 4. Environment Configuration

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database

Edit the `.env` file and update the database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=51crm
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=51crm
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 6. Create Database

Create the database specified in your `.env` file:

**For MySQL:**
```bash
mysql -u your_username -p
```
```sql
CREATE DATABASE 51crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**For PostgreSQL:**
```bash
psql -U your_username
```
```sql
CREATE DATABASE 51crm;
\q
```

### 7. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will:
- Create all database tables
- Set up roles and permissions
- Create default pipeline stages
- Create an admin user with default credentials

### 8. Storage Link

Create the symbolic link for file storage:

```bash
php artisan storage:link
```

### 9. Compile Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 10. File Permissions

Ensure the following directories are writable:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

If you're on Linux/Mac:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 11. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Default Credentials

After running the seeders, you can log in with:

- **Email**: admin@51crm.com
- **Password**: password

**⚠️ IMPORTANT**: Change these credentials immediately after your first login!

## Post-Installation Steps

### 1. Change Admin Credentials

1. Log in with the default credentials
2. Navigate to Profile (click your name in the top right)
3. Update your name, email, and password

### 2. Configure Email (Optional)

Update your `.env` file with email settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@51crm.com
MAIL_FROM_NAME="51CRM"
```

### 3. Set Up Queue Worker (Optional)

For background job processing:

```bash
php artisan queue:work
```

For production, use Supervisor to keep the queue worker running.

### 4. Set Up Scheduler (Optional)

Add this to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Production Deployment

### Additional Steps for Production

1. **Set Application to Production Mode**

```env
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize Application**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. **Web Server Configuration**

**Apache (.htaccess is included)**
- Ensure `mod_rewrite` is enabled
- Point document root to `/public` directory

**Nginx**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path-to-your-project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

4. **SSL Certificate (Recommended)**

Use Let's Encrypt for free SSL:

```bash
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

## Troubleshooting

### Common Issues

**1. Permission Denied Errors**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

**2. Database Connection Failed**
- Verify database credentials in `.env`
- Ensure database server is running
- Check firewall settings

**3. 500 Internal Server Error**
- Check `storage/logs/laravel.log` for errors
- Ensure `APP_DEBUG=true` in `.env` (for development only)
- Clear all caches: `php artisan cache:clear`

**4. Mix Manifest Not Found**
```bash
npm run build
```

**5. Class Not Found Errors**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan optimize
```

**6. Migration Errors**
```bash
# Reset database and re-run migrations
php artisan migrate:fresh --seed
```

## Updating the Application

When pulling new changes:

```bash
git pull origin main
composer install
npm install
php artisan migrate
npm run build
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## System Requirements Summary

| Requirement | Minimum Version |
|-------------|----------------|
| PHP | 8.1 |
| MySQL | 5.7 |
| PostgreSQL | 10 |
| Composer | 2.x |
| Node.js | 16.x |
| NPM | 8.x |

## Support

For issues or questions:
- Check the [README.md](README.md) for documentation
- Review Laravel documentation: https://laravel.com/docs/10.x
- Contact the development team

## Security Note

**Never commit your `.env` file to version control!**

Always use strong passwords and keep your application dependencies up to date:

```bash
composer update
npm update
```

## Next Steps

Once installed, you can:
1. Create additional users and assign roles
2. Start adding leads and clients
3. Configure pipeline stages as needed
4. Customize the application to your needs
5. Set up AI integrations for automated follow-ups

Enjoy using 51CRM! 🚀
