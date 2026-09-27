# Learning Management System (if0_41817906_lms)

A complete lms built with plain PHP and MySQL, featuring role-based access control and modern UI.

## 🚀 Quick Start (Development)

1. **Database Setup**: Import `database` into MySQL
2. **Start XAMPP**: Apache + MySQL
3. **Access**: `http://***********_lms`
4. **Login**: Use test accounts below

## 📋 Test Accounts

```
Admin:    admin@lms.com    / password
Faculty:  faculty@lms.com  / password
Student:  student@lms.com / password
```

## 🏭 Production Deployment

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Apache/Nginx web server
- SSL certificate (recommended)

### Step 1: Server Preparation

```bash
# Clone or upload files to your web 
cd /var/www/html/
git clone https://your-repo/if0_41817906_lms.git
cd lms

# Run deployment script
chmod +x deploy.sh
./deploy.sh
```

### Step 2: Environment Configuration

```bash
# Copy and edit environment file
cp .env.example .env
nano .env
```

**Required .env settings:**
```env
# Database
DB_HOST=your-db-host
DB_USER=your-db-user
DB_PASS=your-secure-password
DB_NAME=lms

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Security
SESSION_SECURE=true
SESSION_HTTPONLY=true
SESSION_SAMESITE=Strict
```

### Step 3: Database Setup

```bash
# Import database schema
mysql -u your-user -p your-database <lms_setup.sql
```

### Step 4: Web Server Configuration

#### Apache (.htaccess already included)
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    Document/var/www/html/_lms

    <Directory /var/www/html/_lms>
        AllowOverride All
        Require all granted
    </Directory>

    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    Document /var/www/html/lms

    SSLEngine on
    SSLCertificateFile /path/to/cert.pem
    SSLCertificateKeyFile /path/to/private.key

    <Directory /var/www/html/_lms>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    if0_41817906 /var/www/html/lms;
    index index.php;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_if0_41817906$fastcgi_script_name;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ ^/(config|logs|\.env) {
        deny all;
    }
}
```

### Step 5: Security Hardening

```bash
# Set proper ownership
chown -R www-data:www-data /var/www/html/lms

# Secure sensitive files
chmod 600 .env
chmod 600 config/*.php
chmod 755 logs/
chmod 755 uploads/

# Install security updates
apt update && apt upgrade -y
```

### Step 6: SSL Configuration

1. **Obtain SSL Certificate** (Let's Encrypt recommended):
```bash
apt install certbot python3-certbot-apache
certbot --apache -d yourdomain.com
```

2. **Test SSL**: https://www.ssllabs.com/ssltest/

### Step 7: Final Testing

1. **Access your site**: `https://yourdomain.com`
2. **Test login** with admin account
3. **Check file uploads** and permissions
4. **Verify all user roles** work correctly
5. **Test responsive design** on mobile devices

## 🔧 Configuration Options

### File Permissions

```
755 - Directories
644 - PHP files
600 - Config files (.env)
755 - Upload directories
```

## 🛠 Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check `.env` database credentials
   - Verify MySQL is running
   - Check database exists

2. **File Upload Issues**
   - Check upload directory permissions
   - Verify PHP upload limits
   - Check file size restrictions

3. **Session Issues**
   - Check session save path permissions
   - Verify PHP session configuration
   - Check for conflicting session settings

4. **HTTPS Issues**
   - Verify SSL certificate is valid
   - Check web server SSL configuration
   - Ensure all assets load over HTTPS

### Logs

- **PHP Errors**: `logs/php_errors.log`
- **Web Server Logs**: Check Apache/Nginx error logs
- **Application Logs**: Check custom logging in your code

## 📞 Support

For issues and questions:
1. Check this README
2. Review error logs
3. Test in development environment first
4. Check file permissions and configuration

## 📄 License

See LICENSE.md for licensing information.

### Step 2: Configure Database Connection

Edit `config/db.php`:

### Step 3: Start XAMPP

1. Open XAMPP Control Panel
2. Start Apache and MySQL services
3. Access _lms at: http://********************_lms

## File Structure

```

├── config/
│   └── db.php              # Database connection
├── includes/
│   ├── header.php          # Header template
│   ├── footer.php          # Footer template
│   └── functions.php       # Utility functions
├── admin/
│   ├── dashboard.php       # Admin dashboard
│   ├── users.php           # User management
│   ├── courses.php         # Course management
│   ├── semesters.php       # Semester management
│   └── announcements.php   # Post announcements
├── faculty/
│   ├── dashboard.php       # Faculty dashboard
│   ├── courses.php         # View courses and students
│   ├── assignments.php     # Create assignments
│   ├── materials.php       # Upload study materials
│   └── submissions.php     # Grade submissions
├── student/
│   ├── dashboard.php       # Student dashboard
│   ├── courses.php         # View enrolled courses
│   ├── assignments.php     # View assignments
│   ├── submit_assignment.php # Submit assignments
│   ├── materials.php       # Download materials
│   └── grades.php          # View grades
├── uploads/
│   ├── assignments/        # Student submissions
│   └── materials/          # Course materials
├── css/
│   └── style.css           # Main stylesheet
├── login.php               # Login page
├── logout.php              # Logout script
├── index.php               # Main routing
└── if0_41817906_lms_setup.sql           # Database setup
```

## Database Schema

### Tables

- **users** - Store user information (admin, faculty, student)
- **departments** - Academic departments
- **courses** - Course information
- **semesters** - Academic semesters
- **semester_courses** - Link faculty to courses in semesters
- **enrollments** - Student enrollment in courses
- **assignments** - Course assignments
- **submissions** - Student assignment submissions
- **notes** - Study materials/resources
- **announcements** - System announcements
- **mcq_questions** - Multiple choice questions
- **mcq_answers** - Student answers
- **systemsettings** - System configuration

## Key Functions

### Authentication
- `isLoggedIn()` - Check if user is logged in
- `getCurrentRole()` - Get current user role
- `getCurrentUserId()` - Get current user ID
- `checkRole($role)` - Enforce role-based access

### Data Retrieval
- `getUserById()` - Get user details
- `getCourseById()` - Get course details
- `getAllCourses()` - Get all courses
- `getFacultyCourses()` - Get faculty's courses
- `getStudentCourses()` - Get student's courses
- `getAssignmentsByCourse()` - Get course assignments
- `getNotesByCourse()` - Get course materials
- `getAnnouncements()` - Get system announcements

### Utilities
- `sanitize()` - Sanitize input data
- `formatDate()` - Format dates for display

## Security Features

- PDO prepared statements prevent SQL injection
- Password hashing using PHP's password_hash()
- Session-based authentication
- Input sanitization
- Role-based access control

## API Endpoints

All operations use PHP forms and redirects. No REST API endpoints.

## Notes

- All file uploads are stored in `/uploads/` directory
- Default password for all test users: "password"
- To change passwords, use password_hash() function
- Maximum file upload size can be configured in `php.ini`

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify database credentials in `config/db.php`
- Ensure `lms` database is created

### Login Issues
- Verify test user credentials
- Check `config/db.php` settings
- Clear browser cookies and try again

### File Upload Issues
- Check `/uploads/` directory permissions (755)
- Verify PHP `upload_max_filesize` setting
- Check available disk space

## Future Enhancements

- Email notifications
- Real-time notifications
- Assignment submission reminders
- Grade reports and analytics
- Mobile application
- API for third-party integration

## Support

For issues or questions, check the code comments or review the file documentation.

---

Built with PHP and MySQL | 2024
