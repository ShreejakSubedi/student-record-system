<?php
/**
 * Delete Records Page
 * Handles deletion of students, grades, and attendance
 */

declare(strict_types=1);

// Authentication check
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /fullstack2/student-record-system/');
    exit;
}

require_once '../config/db.php';
require_once '../config/csrf.php';

use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Controllers\StudentController;
use App\Controllers\GradeController;
use App\Controllers\AttendanceController;

try {
    $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$id) {
        throw new Exception('Invalid ID');
    }
    
    $studentModel = new Student($pdo);
    $gradeModel = new Grade($pdo);
    $attendanceModel = new Attendance($pdo);
    
    // If called via POST, verify CSRF token
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST[CSRFToken::name()] ?? '';
        if (!CSRFToken::verify($token)) {
            header('Location: index.php?error=' . urlencode('Invalid request (CSRF token mismatch).'));
            exit;
        }
    }

    switch ($type) {
        case 'student':
            $controller = new StudentController($studentModel, $gradeModel, $attendanceModel);
            $result = $controller->deleteStudent($id);
            break;
            
        case 'grade':
            $controller = new GradeController($gradeModel, $studentModel);
            $result = $controller->deleteGrade($id);
            break;
            
        case 'attendance':
            $controller = new AttendanceController($attendanceModel, $studentModel);
            $result = $controller->deleteAttendance($id);
            break;
            
        default:
            throw new Exception('Invalid type');
    }
    
    if ($result['success']) {
        header('Location: index.php?message=' . urlencode($result['message']));
    } else {
        header('Location: index.php?error=' . urlencode($result['message']));
    }
    exit;
    
} catch (Exception $e) {
    error_log('Error in delete: ' . $e->getMessage());
    header('Location: index.php?error=' . urlencode('An error occurred'));
    exit;
}
