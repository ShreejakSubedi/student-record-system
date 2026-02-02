# Student Record Management System
**A Complete MVC-based Student Management Solution**

> Built with PHP 8.0+, MySQL, Twig Template Engine, and AJAX  
> Following modern web development best practices

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [API Endpoints](#api-endpoints)
- [Security](#security)
- [Project Structure](#project-structure)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Overview

The Student Record Management System is a comprehensive web application designed to manage student information, academic grades, and attendance records. It follows the **Model-View-Controller (MVC)** architectural pattern and uses the **Twig template engine** for clean separation between business logic and presentation.

### Key Highlights:
- ✅ **Full CRUD Operations** on students, grades, and attendance
- ✅ **MVC Architecture** with separation of concerns
- ✅ **100% SQL Injection Prevention** using prepared statements
- ✅ **XSS Protection** through Twig's auto-escaping
- ✅ **Real-time Search** with Ajax autocomplete
- ✅ **Responsive Design** for all devices
- ✅ **Database Views** for reporting
- ✅ **Stored Procedures** for complex queries

---

## ✨ Features

### 1. Student Management
- **Add Students:** Register new students with complete information
- **Edit Students:** Update student details (name, contact, class, status)
- **Delete Students:** Remove students with cascade delete for related records
- **View Students:** Display all students in a formatted table
- **Search Students:** Find students by name, email, or roll number

### 2. Grade Management
- **Record Grades:** Add grades for various subjects
- **Automatic Grade Calculation:** System calculates letter grades and percentages
- **Grade History:** View complete grade history for each student
- **Multiple Subjects:** Support for unlimited subjects per student
- **Semester-based:** Organize grades by semester

### 3. Attendance Management
- **Mark Attendance:** Record daily attendance status
- **Bulk Marking:** Mark attendance for multiple students at once
- **Attendance Statistics:** Calculate attendance percentages
- **Attendance History:** View complete attendance records
- **Status Options:** Present, Absent, or Late

### 4. Search & Reporting
- **Ajax Autocomplete:** Real-time search suggestions
- **Advanced Filtering:** Filter by class and status
- **Performance Summary:** View student performance at a glance
- **Attendance Summary:** Generate attendance statistics

---

## 🛠️ Technology Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.0+ | Backend Language |
| MySQL | 5.7+ | Database |
| Twig | 3.15+ | Template Engine |
| JavaScript (ES6+) | - | Frontend Interactivity |
| CSS3 | - | Styling |
| Fetch API | - | AJAX Requests |

---

## 📦 Installation

### Prerequisites
- **PHP 8.0** or higher
- **MySQL 5.7** or higher
- **Composer** (for dependency management)
- **XAMPP** or similar local development environment

### Step 1: Clone/Extract Project
```bash
# Extract the project to your webroot
cd c:\xampp\htdocs\fullstack2\student-record-system
```

### Step 2: Install Dependencies
```bash
# Install Twig using Composer
composer install
```

### Step 3: Create Database
```bash
# Open phpMyAdmin or MySQL client
# Create a new database named 'student_records'
# Import the database.sql file
```

**Using phpMyAdmin:**
1. Navigate to `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Name it `student_records`
4. Import `database.sql`:
   - Select the database
   - Click "Import"
   - Choose `database.sql`
   - Click "Go"

**Using MySQL Command Line:**
```bash
mysql -u root -p
CREATE DATABASE student_records;
USE student_records;
SOURCE C:/path/to/database.sql;
```

### Step 4: Configure Database
Edit [config/db.php](config/db.php):
```php
const DB_HOST = 'localhost';  // Your host
const DB_USER = 'root';        // Your username
const DB_PASS = '';            // Your password
const DB_NAME = 'student_records';
```

### Step 5: Access Application
Open your browser and navigate to:
```
http://localhost/student-record-system/public/index.php
```

---

## ⚙️ Configuration

### Database Configuration
File: `config/db.php`

```php
// Database credentials
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'student_records';
```

### Twig Configuration
File: `config/twig.php`

```php
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => $cacheDir ?: false,
    'auto_reload' => true,
    'autoescape' => 'html', // XSS Protection
]);
```

---

## 📖 Usage Guide

### Dashboard
1. Visit the home page to see all students, grades, and attendance
2. View statistics cards showing totals
3. Quick action buttons to add new records

### Adding a Student
1. Click "Add Student" button
2. Fill in all required fields:
   - Roll Number *
   - First Name *
   - Last Name *
   - Email *
   - Phone (optional)
   - Date of Birth (optional)
   - Gender (optional)
   - Class *
   - Address (optional)
3. Click "Add Student"
4. System validates and saves the record

### Recording Grades
1. Navigate to "Add Grade"
2. Select a student from dropdown
3. Enter subject, marks, and other details
4. System automatically calculates:
   - Percentage
   - Letter grade (A+ to F)
5. Click "Add Grade"

### Marking Attendance
1. Go to "Add Attendance"
2. **Option A (Single):** Select student, date, and status
3. **Option B (Bulk):** Select date, then check status for all students
4. Click "Mark Attendance"

### Searching Records
1. Click "Search" in navigation
2. Enter search term in the search box
3. See autocomplete suggestions
4. Filter by class or status
5. Click on student to view details or edit

---

## 🔌 API Endpoints

### Search Student
```
GET /student-record-system/public/search.php?query=<term>

Response: {
    "success": true,
    "results": [
        {
            "id": 1,
            "roll_number": "STU001",
            "full_name": "Pratik Luitel",
            "email": "pratik@school.com",
            "class": "Class A",
            "status": "Active"
        }
    ]
}
```

### Get All Students
```
GET /student-record-system/public/search.php?action=get_all

Response: {
    "success": true,
    "students": [...]
}
```

### Get Student Details
```
GET /student-record-system/public/search.php?action=get_details&id=<student_id>

Response: {
    "success": true,
    "student": {...},
    "average_grade": 85.50,
    "attendance_percentage": 92.5,
    "grades": [...],
    "attendance": {...}
}
```

### Check Email Exists
```
GET /student-record-system/public/search.php?check_email=<email>

Response: {
    "success": true,
    "exists": true/false
}
```

---

## 🔒 Security

### SQL Injection Prevention
✅ **100% Prepared Statements**
- All database queries use PDO prepared statements
- No string concatenation in SQL queries
- Parameters are properly parameterized

Example:
```php
$stmt = $pdo->prepare('SELECT * FROM students WHERE email = :email');
$stmt->execute([':email' => $email]);
```

### XSS Prevention
✅ **Twig Auto-escaping**
- All template variables are auto-escaped
- HTML special characters are converted to entities
- `{{ user_input }}` is safe

✅ **Server-side Escaping**
- `htmlspecialchars()` for user inputs
- `filter_var()` for email validation
- Input sanitization before storage

### CSRF Protection
✅ **Session Management**
- Sessions are used for state management
- Flash messages for secure communication
- Server-side validation of all requests

### Input Validation
✅ **Client-side Validation**
- HTML5 form attributes
- JavaScript validation
- Real-time feedback

✅ **Server-side Validation**
- All inputs validated before processing
- Type checking
- Length restrictions
- Format validation

### Password & Data Protection
✅ **Best Practices**
- Database credentials in constants (not hardcoded)
- PDO with explicit error modes
- Error logging (no sensitive data in output)

---

## 📁 Project Structure

```
student-record-system/
│
├── 📂 config/                    # Configuration files
│   ├── db.php                   # Database connection
│   └── twig.php                 # Twig setup
│
├── 📂 public/                    # Entry points (accessible from browser)
│   ├── index.php                # Dashboard
│   ├── add_student.php          # Add student form
│   ├── edit.php                 # Edit student form
│   ├── add_grade.php            # Add grade form
│   ├── add_attendance.php       # Mark attendance
│   ├── delete.php               # Delete handler
│   └── search.php               # Search & API endpoint
│
├── 📂 app/                       # Application logic (MVC)
│   ├── 📂 models/               # Data models
│   │   ├── Student.php          # Student model
│   │   ├── Grade.php            # Grade model
│   │   └── Attendance.php       # Attendance model
│   │
│   └── 📂 controllers/          # Business logic
│       ├── StudentController.php
│       ├── GradeController.php
│       └── AttendanceController.php
│
├── 📂 templates/                 # Twig templates
│   ├── base.html.twig           # Master layout
│   ├── home.html.twig           # Dashboard
│   ├── add_student.html.twig
│   ├── edit_student.html.twig
│   ├── add_grade.html.twig
│   ├── add_attendance.html.twig
│   └── search.html.twig
│
├── 📂 assets/                    # Static files
│   ├── 📂 css/
│   │   └── main.css             # Consolidated styles
│   │
│   └── 📂 js/
│       ├── ajax.js              # AJAX functions
│       └── validation.js        # Form validation
│
├── vendor/                       # Composer dependencies
│   └── twig/
│
├── database.sql                  # Database schema & sample data
├── composer.json                 # Composer dependencies
├── README.md                     # This file
└── REQUIREMENTS_CHECKLIST.md    # Requirements checklist

```

---

## 🆘 Troubleshooting

### Issue: "Database connection failed"
**Solution:**
1. Check if MySQL server is running
2. Verify credentials in `config/db.php`
3. Ensure database `student_records` exists
4. Check if database user has appropriate permissions

### Issue: "Twig templates not found"
**Solution:**
1. Verify `templates/` directory exists
2. Check file permissions (should be readable)
3. Ensure template files have `.twig` extension
4. Check paths in `config/twig.php`

### Issue: "Composer autoloader not found"
**Solution:**
```bash
# Reinstall dependencies
composer install

# Or download Twig manually
# Place in vendor/twig/ directory
```

### Issue: "404 Page Not Found"
**Solution:**
1. Verify file exists at specified path
2. Check file permissions
3. Ensure using full path: `/student-record-system/public/index.php`
4. Verify `.htaccess` configuration (if using URL rewriting)

### Issue: "AJAX requests not working"
**Solution:**
1. Open browser console (F12)
2. Check for CORS errors
3. Verify URL paths in JavaScript
4. Ensure PHP files are returning JSON
5. Check network requests in Network tab

### Issue: "Email validation not working"
**Solution:**
1. Verify `filter_var()` function is available
2. Check for JavaScript validation conflicts
3. Ensure server-side validation is running
4. Review browser console for errors

---

## 📊 Database Schema

### Students Table
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    address TEXT,
    class VARCHAR(50),
    enrollment_date DATE,
    status ENUM('Active', 'Inactive', 'Graduated'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Grades Table
```sql
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(100),
    semester VARCHAR(20),
    marks_obtained DECIMAL(5,2),
    total_marks DECIMAL(5,2),
    grade VARCHAR(2),
    percentage DECIMAL(5,2),
    exam_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

### Attendance Table
```sql
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    attendance_date DATE,
    status ENUM('Present', 'Absent', 'Late'),
    remarks TEXT,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, attendance_date)
);
```

---

## 📝 Sample Data

The `database.sql` file includes:
- **10 Sample Students** with complete information
- **12 Sample Grades** across different subjects
- **14 Sample Attendance** records

---

## 🚀 Deployment

### Prerequisites for Server Deployment
- PHP 8.0+ with PDO support
- MySQL 5.7+
- Composer installed on server
- Write permissions for logs

### Deployment Steps
1. Upload all files to web root
2. Run `composer install` on server
3. Create database and import `database.sql`
4. Update `config/db.php` with server credentials
5. Set appropriate file permissions
6. Test endpoints

---

## 📜 License

This project is created for educational purposes as part of a Full Stack Development assignment.

---

## 📧 Contact & Support

**Student:** Shreejak Subedi
**Email:** shreejak666@gmail.com  
**Project:** Student Record Management System  
**Date:** January 2026  

---

## ✅ Checklist

- [x] Full CRUD functionality
- [x] MVC architecture
- [x] Twig templating
- [x] SQL injection prevention
- [x] XSS protection
- [x] AJAX autocomplete search
- [x] Form validation
- [x] Responsive design
- [x] Database schema with relationships
- [x] Comprehensive documentation
- [x] Error handling
- [x] Security best practices

---

**Built with ❤️ using PHP, MySQL, and Twig**
