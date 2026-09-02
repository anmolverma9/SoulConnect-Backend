# LINUX PRODUCTION DEPLOYMENT GUIDE

## 1. System Requirements

- **OS**: Ubuntu 22.04 LTS / 24.04 LTS
- **PHP**: PHP 8.3+ with extensions: `php8.3-fpm`, `php8.3-mysql`, `php8.3-redis`, `php8.3-mbstring`, `php8.3-xml`, `php8.3-curl`, `php8.3-bcmath`, `php8.3-gd`, `php8.3-zip`
- **Database**: MySQL 8.0+ / MariaDB 10.6+
- **Cache & Queues**: Redis 7+
- **Web Server**: Nginx with SSL (Let's Encrypt / Certbot)
- **Process Supervisor**: Supervisor

---

## 2. Server Installation Commands

```bash
# 1. Update Packages and install PHP 8.3 + Extensions
sudo apt update && sudo apt upgrade -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-redis \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-bcmath php8.3-gd \
    php8.3-zip composer nginx redis-server supervisor git

# 2. Clone/Deploy Application
cd /var/www
git clone <your-repo-url> dating-backend
cd /var/www/dating-backend

# 3. Install Production Dependencies
composer install --no-dev --optimize-autoloader

# 4. Environment & Storage Configuration
cp .env.example .env
nano .env # Set APP_ENV=production, DB credentials, Redis, FCM, Google Play
php artisan key:generate
php artisan storage:link

# 5. Run Database Migrations and Seeders
php artisan migrate --force
php artisan db:seed --force

# 6. File Permissions
sudo chown -R www-data:www-data /var/www/dating-backend/storage /var/www/dating-backend/bootstrap/cache
sudo chmod -R 775 /var/www/dating-backend/storage /var/www/dating-backend/bootstrap/cache

# 7. Cache Optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 3. Nginx Configuration (`/etc/nginx/sites-available/dating-api.conf`)

```nginx
server {
    listen 80;
    server_name api.datingapp.example.com;
    root /var/www/dating-backend/public;

    index index.php;

    charset utf-8;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Supervisor Configuration for Queues & Reverb

### Queue Worker (`/etc/supervisor/conf.d/dating-worker.conf`):
```ini
[program:dating-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dating-backend/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/supervisor/dating-worker.log
stopwaitsecs=3600
```

### Reverb WebSocket Host (`/etc/supervisor/conf.d/dating-reverb.conf`):
```ini
[program:dating-reverb]
command=php /var/www/dating-backend/artisan reverb:start --host="0.0.0.0" --port=8080
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/supervisor/dating-reverb.log
```

Enable processes:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```
