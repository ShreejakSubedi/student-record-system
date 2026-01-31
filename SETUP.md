# Quick Setup Guide

## 5-Minute Installation

### Step 1: Install Composer Dependencies
```bash
cd c:\xampp\htdocs\fullstack2\student-record-system
composer install
```

### Step 2: Create Database
```bash
# Option A: Using phpMyAdmin
# 1. Go to http://localhost/phpmyadmin
# 2. Create new database: student_records
# 3. Import database.sql

# Option B: Using MySQL Command Line
mysql -u root -p < database.sql
```

### Step 3: Update Database Credentials (if needed)
Edit `config/db.php`:
```php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';  # Your password
const DB_NAME = 'student_records';
```

### Step 4: Start XAMPP
```bash
# Start Apache and MySQL from XAMPP Control Panel
# Or via command line:
xampp-control.exe
```

### Step 5: Access Application
Open your browser:
```
http://localhost/student-record-system/public/index.php
```

---

## Default Login

No login required! All features are accessible immediately.

**Sample Account:**
- Roll Number: STU001
- Name: Pratik Luitel
- Email: pratik.luitel@school.com

---

## File Permissions

Make sure these directories are writable:
```bash
chmod -R 755 templates/
chmod -R 755 assets/
chmod -R 755 config/
```

---

## Troubleshooting

### Error: "Call to undefined function..."
→ Run `composer install` to install dependencies

### Error: "Database connection failed"
→ Check MySQL is running and credentials in `config/db.php`

### Error: "No such file or directory"
→ Verify all directories exist and paths are correct

### Page shows blank
→ Check browser console (F12) for errors

---

## Next Steps

1. ✅ Add your first student
2. ✅ Add grades for students
3. ✅ Mark attendance
4. ✅ Try the search functionality
5. ✅ Edit and delete records

---

## Questions?

Refer to `README.md` for comprehensive documentation.
