# API Documentation

## Student Record Management System - API Reference

---

## Base URL
```
http://localhost/student-record-system/public/
```

---

## Endpoints

### Search Endpoints

#### 1. Search Students (Autocomplete)
**Endpoint:** `GET /search.php?query=<search_term>`

**Parameters:**
- `query` (string, min 2 characters): Search term for name, email, or roll number

**Response (200 OK):**
```json
{
    "success": true,
    "results": [
        {
            "id": 1,
            "roll_number": "STU001",
            "full_name": "Pratik Luitel",
            "email": "pratik.luitel@school.com",
            "class": "Class A",
            "status": "Active"
        }
    ]
}
```

**Example:**
```javascript
fetch('/student-record-system/public/search.php?query=Pratik')
    .then(r => r.json())
    .then(data => console.log(data));
```

---

#### 2. Get All Students
**Endpoint:** `GET /search.php?action=get_all`

**Response (200 OK):**
```json
{
    "success": true,
    "students": [
        {
            "id": 1,
            "roll_number": "STU001",
            "first_name": "Pratik",
            "last_name": "Luitel",
            "email": "pratik@school.com",
            "phone": "9841234567",
            "date_of_birth": "2003-05-15",
            "gender": "Male",
            "address": "123 Main Street",
            "class": "Class A",
            "enrollment_date": "2023-08-01",
            "status": "Active",
            "created_at": "2024-01-15 10:30:00",
            "updated_at": "2024-01-15 10:30:00"
        }
    ]
}
```

---

#### 3. Get Student Details
**Endpoint:** `GET /search.php?action=get_details&id=<student_id>`

**Parameters:**
- `action` (string): `get_details`
- `id` (integer): Student ID

**Response (200 OK):**
```json
{
    "success": true,
    "student": {
        "id": 1,
        "roll_number": "STU001",
        "first_name": "Pratik",
        "last_name": "Luitel",
        "email": "pratik@school.com",
        "class": "Class A",
        "status": "Active",
        "created_at": "2024-01-15 10:30:00",
        "updated_at": "2024-01-15 10:30:00"
    },
    "average_grade": 85.50,
    "attendance_percentage": 92.5,
    "grades": [
        {
            "id": 1,
            "subject": "Mathematics",
            "semester": "Semester 1",
            "marks_obtained": 88,
            "total_marks": 100,
            "grade": "A",
            "percentage": 88.00,
            "exam_date": "2023-12-15"
        }
    ],
    "attendance": {
        "present_days": 20,
        "absent_days": 2,
        "late_days": 1,
        "total_days": 23,
        "attendance_percentage": 92.50
    }
}
```

**Example:**
```javascript
fetch('/student-record-system/public/search.php?action=get_details&id=1')
    .then(r => r.json())
    .then(data => console.log(data.average_grade));
```

---

#### 4. Check Email Exists
**Endpoint:** `GET /search.php?check_email=<email>`

**Parameters:**
- `check_email` (string): Email address to check

**Response (200 OK):**
```json
{
    "success": true,
    "exists": true
}
```

**Example:**
```javascript
// Validate email uniqueness
fetch('/student-record-system/public/search.php?check_email=student@example.com')
    .then(r => r.json())
    .then(data => {
        if (data.exists) {
            showError('Email already registered');
        }
    });
```

---

## Form Endpoints

### Student Operations

#### 1. Add Student
**Endpoint:** `POST /add_student.php`

**Request Body (form-data):**
```
roll_number: STU011
first_name: John
last_name: Doe
email: john@school.com
phone: 9841234567
date_of_birth: 2003-06-15
gender: Male
address: 456 Street
class: Class B
enrollment_date: 2023-08-01
status: Active
```

**Response (Redirect to /index.php with message)**

**Validation Rules:**
- Roll number: Required, unique
- First/Last name: Required, min 2 chars
- Email: Required, valid format, unique
- Phone: Optional, min 10 chars
- Class: Required

---

#### 2. Edit Student
**Endpoint:** `POST /edit.php?id=<student_id>`

**Request Body (form-data):**
```
roll_number: STU001
first_name: Pratik
last_name: Luitel
email: pratik@school.com
phone: 9841234567
date_of_birth: 2003-05-15
gender: Male
address: 123 Main Street
class: Class A
status: Active
```

**Response (Redirect to /index.php with message)**

---

#### 3. Delete Student
**Endpoint:** `GET /delete.php?type=student&id=<student_id>`

**Parameters:**
- `type` (string): `student`, `grade`, or `attendance`
- `id` (integer): Record ID

**Response (Redirect to /index.php with message)**

**Note:** Deleting a student also deletes all associated grades and attendance records (CASCADE delete).

---

### Grade Operations

#### 1. Add Grade
**Endpoint:** `POST /add_grade.php`

**Request Body (form-data):**
```
student_id: 1
subject: Mathematics
semester: Semester 1
marks_obtained: 88
total_marks: 100
exam_date: 2024-01-15
```

**Response (Redirect to /index.php)**

**Auto-calculated:**
- Percentage: (marks_obtained / total_marks) * 100
- Grade: A+ (95+), A (90-94), B+ (85-89), B (80-84), etc.

---

### Attendance Operations

#### 1. Mark Single Attendance
**Endpoint:** `POST /add_attendance.php`

**Request Body (form-data):**
```
student_id: 1
attendance_date: 2024-01-15
status: Present
remarks: On time
```

**Response (Redirect to /index.php)**

**Valid Status Values:**
- `Present`
- `Absent`
- `Late`

---

#### 2. Mark Bulk Attendance
**Endpoint:** `POST /add_attendance.php?bulk=1`

**Request Body (form-data):**
```
bulk_date: 2024-01-15
attendance[1]: Present
attendance[2]: Absent
attendance[3]: Late
attendance[4]: Present
```

**Response (Redirect to /index.php)**

---

## Response Format

### Success Response
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {}
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": "Error for this field"
    }
}
```

---

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 302 | Redirect - After successful POST |
| 400 | Bad Request - Invalid parameters |
| 404 | Not Found - Resource doesn't exist |
| 500 | Server Error - Internal error |

---

## Error Handling

### Common Errors

**Error: Student not found**
```json
{
    "success": false,
    "message": "Student not found"
}
```

**Error: Invalid email format**
```json
{
    "success": false,
    "errors": {
        "email": "Invalid email format"
    }
}
```

**Error: Duplicate email**
```json
{
    "success": false,
    "errors": {
        "email": "This email already exists"
    }
}
```

**Error: Database connection failed**
```
"Database connection failed. Please contact administrator."
```

---

## JavaScript Examples

### Autocomplete Search
```javascript
document.getElementById('searchInput').addEventListener('input', async (e) => {
    const query = e.target.value;
    if (query.length < 2) return;
    
    const response = await fetch(
        `/student-record-system/public/search.php?query=${encodeURIComponent(query)}`
    );
    const data = await response.json();
    
    if (data.success) {
        displayResults(data.results);
    }
});
```

### Fetch Student Details
```javascript
async function getStudentDetails(studentId) {
    const response = await fetch(
        `/student-record-system/public/search.php?action=get_details&id=${studentId}`
    );
    const data = await response.json();
    
    if (data.success) {
        console.log(`Student: ${data.student.first_name} ${data.student.last_name}`);
        console.log(`Average Grade: ${data.average_grade}%`);
        console.log(`Attendance: ${data.attendance_percentage}%`);
    }
}
```

### Validate Email
```javascript
async function validateEmail(email) {
    const response = await fetch(
        `/student-record-system/public/search.php?check_email=${encodeURIComponent(email)}`
    );
    const data = await response.json();
    return !data.exists;
}
```

### Submit Form with Validation
```javascript
async function submitForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const response = await fetch(event.target.action, {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    
    if (data.success) {
        showSuccess(data.message);
        setTimeout(() => {
            window.location.href = '/student-record-system/public/index.php';
        }, 2000);
    } else {
        showErrors(data.errors);
    }
}
```

---

## Rate Limiting

No rate limiting is implemented. For production, consider adding:
- Request throttling
- IP-based rate limiting
- API key authentication

---

## Authentication

Currently, there is no authentication. In production, implement:
- User login system
- JWT tokens
- Session management
- Role-based access control

---

## CORS

CORS is not configured. All requests must be from the same origin.

---

## Versioning

This API follows **version 1.0** of the Student Record Management System.

---

## Changelog

### Version 1.0 (January 2026)
- Initial release
- Basic CRUD operations
- Search functionality
- Bulk operations

---

## Support

For issues or questions, refer to:
- **README.md** - General documentation
- **REQUIREMENTS_CHECKLIST.md** - Feature list
- **Code comments** - Implementation details

---

*API Documentation for Student Record Management System v1.0*
