# JB Construction Website - Backend Setup Guide

## Overview
This is a complete construction website with a PHP/SQLite backend admin panel for managing photos and reviews.

## Files Included
- `construction-landing-page.html` - Main website (frontend)
- `init_db.php` - Database initialization script
- `admin_login.php` - Admin login page
- `admin_panel.php` - Admin panel for managing photos and reviews
- `logout.php` - Logout functionality
- `get_photos.php` - API endpoint to fetch photos
- `get_reviews.php` - API endpoint to fetch reviews
- `construction_site.db` - SQLite database (created after running init_db.php)

## Requirements
- PHP 7.4 or higher with SQLite3 extension enabled
- Web server (Apache, Nginx, or PHP built-in server)
- Write permissions for the website directory

## Installation Steps

### 1. Upload Files
Upload all PHP files to your web server in the same directory as your HTML file.

### 2. Initialize the Database
Open your browser and navigate to:
```
http://yourdomain.com/init_db.php
```

This will:
- Create the SQLite database file (`construction_site.db`)
- Set up the necessary tables (photos, reviews, admin_users)
- Create a default admin account

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

**IMPORTANT:** Delete or rename `init_db.php` after running it to prevent unauthorized database resets!

### 3. Set File Permissions
Make sure the directory is writable so PHP can:
- Create the database file
- Create the `uploads/` folder for photos
- Upload images

On Linux/Mac:
```bash
chmod 755 /path/to/website
chmod 666 construction_site.db
chmod 755 uploads/
```

### 4. Access the Admin Panel
Navigate to:
```
http://yourdomain.com/admin_login.php
```

Login with the default credentials and **immediately change your password**!

### 5. Rename the Landing Page (Optional)
Rename `construction-landing-page.html` to `index.html` to make it your homepage:
```
mv construction-landing-page.html index.html
```

## Using the Admin Panel

### Adding Photos
1. Click "Select Photo" and choose an image file
2. Add a description (alt text) for accessibility
3. Click "Upload Photo"
4. Photos will appear in the gallery on your main website

### Adding Reviews
1. Fill in customer name
2. Enter location (e.g., "Lancaster, PA")
3. Select star rating (1-5 stars)
4. Write the review text
5. Click "Add Review"
6. Reviews will appear in the carousel on your main website

### Deleting Content
- Click the "Delete" button under any photo or review
- Confirm the deletion
- The item will be removed from both the admin panel and the website

## Security Recommendations

### 1. Change Default Password
After first login, change the admin password in the database:
```php
<?php
$db = new SQLite3('construction_site.db');
$new_password = password_hash('your_new_secure_password', PASSWORD_DEFAULT);
$db->exec("UPDATE admin_users SET password = '$new_password' WHERE username = 'admin'");
echo "Password updated!";
?>
```

### 2. Restrict Admin Access
Add `.htaccess` file to protect admin pages:
```apache
<Files "admin_*.php">
    # Require IP address or authentication
    Require ip 123.456.789.0
</Files>
```

### 3. Enable HTTPS
Always use HTTPS in production to protect login credentials.

### 4. Regular Backups
Backup your database file regularly:
```bash
cp construction_site.db construction_site_backup_$(date +%Y%m%d).db
```

## Testing Locally

### Using PHP Built-in Server
```bash
cd /path/to/website
php -S localhost:8000
```

Then visit: `http://localhost:8000`

## Troubleshooting

### Photos Not Showing
- Check that `uploads/` directory exists and is writable
- Verify images uploaded successfully in admin panel
- Check browser console for JavaScript errors
- Ensure `get_photos.php` is accessible

### Reviews Not Showing
- Check browser console for errors
- Verify `get_reviews.php` returns JSON data
- Make sure database has reviews added

### Can't Login to Admin
- Verify `construction_site.db` exists
- Check file permissions
- Ensure SQLite3 PHP extension is installed:
  ```bash
  php -m | grep sqlite3
  ```

### Database Errors
- Delete `construction_site.db` and run `init_db.php` again
- Check PHP error logs for detailed messages

## Customization

### Change Company Name
Edit these files:
- `construction-landing-page.html` - Update header and content
- `admin_panel.php` - Update page title

### Add More Services
Edit the Services section in `construction-landing-page.html`

### Change Color Scheme
Modify CSS variables in `construction-landing-page.html`:
- `#e67e22` - Orange (primary color)
- `#2c3e50` - Dark blue (header/footer)

## Support

For issues or questions:
1. Check file permissions
2. Review PHP error logs
3. Test with PHP built-in server
4. Verify SQLite3 is installed and enabled

## License
Free to use and modify for your business needs.
