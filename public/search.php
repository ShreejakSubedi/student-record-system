<?php
/**
 * Search and API Endpoint
 * Handles search queries and Ajax requests
 * GET with no query params: renders search form
 * GET with query/action params: returns JSON API responses
 */

declare(strict_types=1);

// Authentication check
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /fullstack2/student-record-system/');
    exit;
}

// Composer autoload (PSR-4)
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/twig.php';
require_once __DIR__ . '/../config/csrf.php';

use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;

// Check if this is an API call (has GET params like query, action, check_email)
$isApiCall = isset($_GET['query']) || isset($_GET['action']) || isset($_GET['check_email']);

if (!$isApiCall) {
    // Render search form page
    echo $twig->render('search.html.twig', [
        'csrf_token' => CSRFToken::generate(),
        'csrf_token_name' => CSRFToken::name(),
    ]);
    exit;
}

// API endpoints - return JSON
header('Content-Type: application/json');

try {
    $studentModel = new Student($pdo);
    $gradeModel = new Grade($pdo);
    $attendanceModel = new Attendance($pdo);
    
    // Handle search query (autocomplete)
    if (isset($_GET['query'])) {
        $query = htmlspecialchars($_GET['query']);
        $results = $studentModel->search($query);
        echo json_encode([
            'success' => true,
            'results' => $results,
        ]);
        exit;
    }
    
    // Handle email check
    if (isset($_GET['check_email'])) {
        $email = htmlspecialchars($_GET['check_email']);
        $exists = $studentModel->emailExists($email);
        echo json_encode([
            'success' => true,
            'exists' => $exists,
        ]);
        exit;
    }
    
    // Handle get all students
    if (isset($_GET['action']) && $_GET['action'] === 'get_all') {
        $students = $studentModel->getAll();
        echo json_encode([
            'success' => true,
            'students' => $students,
        ]);
        exit;
    }
    
    // Handle get student details
    if (isset($_GET['action']) && $_GET['action'] === 'get_details') {
        $studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $student = $studentModel->getById($studentId);
        
        if (!$student) {
            echo json_encode([
                'success' => false,
                'message' => 'Student not found',
            ]);
            exit;
        }
        
        $grades = $gradeModel->getByStudentId($studentId);
        $attendance = $attendanceModel->getAttendancePercentage($studentId);
        
        $averageGrade = 0;
        if (!empty($grades)) {
            $totalPercentage = array_sum(array_column($grades, 'percentage'));
            $averageGrade = round($totalPercentage / count($grades), 2);
        }
        
        echo json_encode([
            'success' => true,
            'student' => $student,
            'average_grade' => $averageGrade,
            'attendance_percentage' => $attendance['attendance_percentage'] ?? 0,
            'grades' => $grades,
            'attendance' => $attendance,
        ]);
        exit;
    }
    
    // Default response
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request',
    ]);
    
} catch (Exception $e) {
    error_log('Error in search: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred',
    ]);
}
