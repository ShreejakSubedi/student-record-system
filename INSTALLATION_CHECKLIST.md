# Deployment & Installation Checklist

## Pre-Installation Requirements
- [ ] PHP 8.0 or higher installed
- [ ] MySQL 5.7 or higher installed
- [ ] Composer installed
- [ ] XAMPP or similar local dev environment running
- [ ] MySQL service is running
- [ ] Apache/Nginx web server is running

---

## Installation Steps

### Step 1: Extract Project
- [ ] Extract project to `c:\xampp\htdocs\fullstack2\student-record-system\`
- [ ] Verify all directories are readable

### Step 2: Install Dependencies
```bash
cd c:\xampp\htdocs\fullstack2\student-record-system
composer install
```
- [ ] Composer install completed successfully
- [ ] `vendor/` directory created
- [ ] `vendor/twig/` directory exists

### Step 3: Create Database
Choose one method:

**Method A: phpMyAdmin**
- [ ] Open `http://localhost/phpmyadmin`
- [ ] Create new database `student_records`
- [ ] Go to Import tab
- [ ] Select `database.sql` file
- [ ] Click "Go" to import
- [ ] Verify tables created (students, grades, attendance)

**Method B: MySQL Command Line**
```bash
mysql -u root -p < database.sql
```
- [ ] Command executed successfully
- [ ] Database `student_records` created
- [ ] All tables imported

### Step 4: Verify Database
- [ ] Connect to MySQL and verify database exists
- [ ] Check students table has 10 sample records
- [ ] Check grades table has 12 sample records
- [ ] Check attendance table has 14 sample records

### Step 5: Update Configuration (if needed)
Edit `config/db.php`:
- [ ] Update DB_HOST (usually 'localhost')
- [ ] Update DB_USER (usually 'root')
- [ ] Update DB_PASS (your password)
- [ ] Update DB_NAME (should be 'student_records')

### Step 6: Set File Permissions
```bash
chmod -R 755 templates/
chmod -R 755 assets/
chmod -R 755 public/
```
- [ ] File permissions set correctly

### Step 7: Test Connection
- [ ] Open `http://localhost/student-record-system/public/index.php`
- [ ] Dashboard loads without errors
- [ ] Sample data displays on dashboard

---

## Verification Tests

### Functionality Tests
- [ ] Dashboard displays all students (10 total)
- [ ] Dashboard displays all grades (12 total)
- [ ] Dashboard displays all attendance records (14 total)
- [ ] Statistics cards show correct totals
- [ ] Add Student button works
- [ ] Add Grade button works
- [ ] Add Attendance button works
- [ ] Search functionality works
- [ ] Filter by class works
- [ ] Filter by status works

### Form Tests
- [ ] Add Student form validates required fields
- [ ] Add Student prevents duplicate emails
- [ ] Edit Student form pre-fills correctly
- [ ] Add Grade auto-calculates percentage
- [ ] Add Grade auto-calculates letter grade
- [ ] Attendance dropdown shows all students
- [ ] Bulk attendance marking works

### Delete Tests
- [ ] Delete student prompts for confirmation
- [ ] Delete student removes record
- [ ] Delete student cascades to related records
- [ ] Delete grade removes record
- [ ] Delete attendance removes record

### Search Tests
- [ ] Autocomplete suggestions appear while typing
- [ ] Search returns matching students
- [ ] Filter by class reduces results
- [ ] Filter by status reduces results
- [ ] Search with no results shows message

### Security Tests
- [ ] SQL injection attempt blocked
- [ ] XSS attempt escaped
- [ ] Invalid email rejected
- [ ] Missing required field rejected
- [ ] Session remains secure

### Browser Tests
- [ ] Application works in Chrome
- [ ] Application works in Firefox
- [ ] Application works in Edge
- [ ] Application works in Safari
- [ ] Mobile view is responsive

---

## Post-Installation

### Documentation Review
- [ ] Read README.md for full documentation
- [ ] Read REQUIREMENTS_CHECKLIST.md for feature list
- [ ] Read API_DOCUMENTATION.md for API reference
- [ ] Read SETUP.md for quick start

### Backup Created
- [ ] Backup of database.sql file created
- [ ] Backup of configuration files created
- [ ] Backup of source code created

### Monitoring Setup (Optional)
- [ ] Error logging configured
- [ ] Access logs enabled
- [ ] Performance monitoring enabled

---

## Common Issues & Solutions

### Issue: Database connection failed
**Solution:**
- [ ] Verify MySQL is running
- [ ] Check credentials in config/db.php
- [ ] Verify database exists
- [ ] Check user permissions

### Issue: Twig templates not found
**Solution:**
- [ ] Verify templates/ directory exists
- [ ] Check file permissions
- [ ] Run `composer install` again
- [ ] Restart web server

### Issue: 404 errors on pages
**Solution:**
- [ ] Verify full path to files
- [ ] Check .htaccess configuration
- [ ] Verify mod_rewrite enabled (if using)
- [ ] Check file exists

### Issue: AJAX requests failing
**Solution:**
- [ ] Check browser console for errors
- [ ] Verify request URLs are correct
- [ ] Check PHP files are returning JSON
- [ ] Verify PHP error logs

### Issue: Blank page displayed
**Solution:**
- [ ] Check browser console (F12) for errors
- [ ] Check PHP error logs
- [ ] Verify all files uploaded
- [ ] Clear browser cache

---

## Production Deployment

If deploying to live server:

- [ ] Update database credentials for production
- [ ] Enable error logging to file
- [ ] Disable debug mode
- [ ] Set appropriate file permissions (644 files, 755 dirs)
- [ ] Use HTTPS instead of HTTP
- [ ] Set up automated backups
- [ ] Configure firewall rules
- [ ] Enable access logging
- [ ] Set up monitoring alerts
- [ ] Implement rate limiting
- [ ] Add authentication layer
- [ ] Update CORS settings if needed
- [ ] Add SSL certificates
- [ ] Optimize database indexes
- [ ] Test failover procedures

---

## Maintenance Checklist (Regular)

### Weekly
- [ ] Check error logs
- [ ] Backup database
- [ ] Verify uptime
- [ ] Review access logs

### Monthly
- [ ] Update PHP packages
- [ ] Update Twig library
- [ ] Review security settings
- [ ] Test backup restoration
- [ ] Analyze performance metrics

### Quarterly
- [ ] Security audit
- [ ] Database optimization
- [ ] Load testing
- [ ] Documentation review
- [ ] Dependency updates

---

## Sign-Off

- [ ] Installation complete
- [ ] All tests passed
- [ ] Documentation reviewed
- [ ] System ready for use

**Installation Date:** _______________  
**Installed By:** _______________  
**Verified By:** _______________  

---

## Contact

For installation support:
- Refer to README.md
- Check API_DOCUMENTATION.md
- Review troubleshooting section
- Contact: pratikluitel03@gmail.com

---

*Installation & Deployment Checklist v1.0*
*Student Record Management System*
