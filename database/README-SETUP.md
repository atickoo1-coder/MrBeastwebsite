# MrBeast Store - Admin Panel Setup Guide

## Prerequisites

- XAMPP (PHP 8+), WAMP, or any local server with PHP 8+ and MySQL
- Web browser
- Code editor

## Step 1: Database Setup

1. Open **phpMyAdmin** (http://localhost/phpmyadmin) or MySQL command line.

2. Run the schema file to create the database and tables:
   ```
   mysql -u root < C:\xampp\htdocs\MrBeastwebsite\database\schema.sql
   ```
   Or paste the contents of `database/schema.sql` into phpMyAdmin SQL tab.

3. (Optional) Run the seed file to populate sample data:
   ```
   mysql -u root < C:\xampp\htdocs\MrBeastwebsite\database\seed.sql
   ```
   Or paste the contents of `database/seed.sql` into phpMyAdmin SQL tab.

## Step 2: Configuration

1. Open `config/database.php` and update database credentials if needed:
   - `DB_HOST` - Default: `127.0.0.1`
   - `DB_NAME` - Default: `mrbeast_store`
   - `DB_USER` - Default: `root`
   - `DB_PASS` - Default: `` (empty for XAMPP)

2. Open `config/app.php` and update:
   - `APP_URL` - Set to your local URL (default: `http://localhost/MrBeastwebsite`)

## Step 3: Start the Server

1. Start Apache and MySQL in XAMPP Control Panel.
2. Verify the site loads at: `http://localhost/MrBeastwebsite/`

## Step 4: Access Admin Panel

1. Navigate to: `http://localhost/MrBeastwebsite/admin/`

2. Login with default credentials:
   - **Username:** `admin`
   - **Password:** `Admin@123`

## Step 5: Create Admin User Manually (if seed was not used)

Run this SQL in phpMyAdmin to create an admin user:

```sql
INSERT INTO admins (username, email, password) VALUES (
    'admin',
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);
```

The default password hash above corresponds to `Admin@123`.

To create a custom admin with a different password:
```php
<?php
echo password_hash('YourPassword123', PASSWORD_DEFAULT);
?>
```

## File Structure

```
project/
├── admin/
│   ├── index.php            # Redirect to dashboard
│   ├── dashboard.php        # Admin dashboard with stats
│   ├── auth/
│   │   ├── login.php        # Login page
│   │   └── logout.php       # Logout handler
│   ├── products/
│   │   ├── index.php        # Product list with pagination
│   │   ├── create.php       # Add product form
│   │   ├── edit.php         # Edit product form
│   │   └── delete.php       # Delete product handler
│   ├── categories/
│   │   ├── index.php        # Category list
│   │   ├── create.php       # Add category form
│   │   ├── edit.php         # Edit category form
│   │   └── delete.php       # Delete category handler
│   └── layouts/
│       ├── header.php       # Dashboard header + sidebar + CSS
│       └── footer.php       # Dashboard footer + JS
├── config/
│   ├── app.php              # App constants
│   └── database.php         # PDO database singleton
├── controllers/
│   ├── AuthController.php   # Login/logout logic
│   ├── CategoryController.php
│   └── ProductController.php
├── models/
│   ├── Admin.php            # Admin queries
│   ├── Category.php         # Category CRUD queries
│   └── Product.php          # Product CRUD queries
├── helpers/
│   ├── Session.php          # Flash messages + session management
│   ├── functions.php        # Utility functions
│   └── validation.php       # Input validation
├── includes/
│   ├── config.php           # Bootstrap loader
│   └── middleware.php       # Auth middleware
├── database/
│   ├── schema.sql           # Database schema
│   ├── seed.sql             # Sample data
│   └── README-SETUP.md      # This file
├── uploads/
│   └── products/            # Product images stored here
└── (existing frontend files)
```

## Features

- **Dashboard:** Total products, active/inactive counts, latest products, quick actions
- **Products:** CRUD with pagination (10 per page), search, sortable columns
- **Categories:** CRUD with cascade delete protection
- **Authentication:** Secure login with hashed passwords and session management
- **Image Upload:** Validates type (JPG/PNG/WebP/GIF), size (max 2MB), unique filenames
- **Security:** PDO prepared statements, CSRF tokens, XSS escaping, password hashing
- **UI:** Bootstrap 5 dark theme matching MrBeast brand (black + orange)

## Security Notes

- Change the default admin password immediately after first login
- Keep `config/database.php` credentials secure in production
- Ensure `uploads/` directory has proper write permissions
- In production, set `APP_ENV` to `'production'` in `config/app.php`
