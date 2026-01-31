<?php
/**
 * Add Attendance Page
 * Form for marking attendance
 */

declare(strict_types=1);

// Authentication check
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /fullstack2/student-record-system/');
    exit;
}

require_once '../config/db.php';
require_once '../config/twig.php';
require_once '../config/csrf.php';

use App\Models\Student;
use App\Models\Attendance;
use App\Controllers\AttendanceController;

$errors = [];
$message = '';

try {
    $studentModel = new Student($pdo);
    $attendanceModel = new Attendance($pdo);
    $controller = new AttendanceController($attendanceModel, $studentModel);
    
    // Get all students for dropdown
    $students = $studentModel->getAll();
    
    // Handle form submission (single record)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['bulk'])) {
        $token = $_POST[CSRFToken::name()] ?? '';
        if (!CSRFToken::verify($token)) {
            throw new Exception('Invalid request (CSRF token mismatch).');
        }

        $result = $controller->createAttendance($_POST);

        if ($result['success']) {
            header('Location: index.php?message=' . urlencode($result['message']));
            exit;
        } else {
            $errors = $result['errors'] ?? [];
            $message = $result['message'] ?? '';
        }
    }
    
    // Handle bulk attendance
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['bulk'])) {
        $token = $_POST[CSRFToken::name()] ?? '';
        if (!CSRFToken::verify($token)) {
            throw new Exception('Invalid request (CSRF token mismatch).');
        }
        $bulkDate = $_POST['bulk_date'] ?? date('Y-m-d');
        $attendanceData = $_POST['attendance'] ?? [];
        $successCount = 0;
        
        foreach ($attendanceData as $studentId => $status) {
            if ($status) {
                $result = $controller->createAttendance([
                    'student_id' => (int)$studentId,
                    'attendance_date' => $bulkDate,
                    'status' => $status,
                    'remarks' => 'Bulk entry',
                ]);
                if ($result['success']) {
                    $successCount++;
                }
            }
        }
        
        if ($successCount > 0) {
            header('Location: index.php?message=' . urlencode("Attendance marked for $successCount students"));
            exit;
        }
    }
    
    // Render template
    echo $twig->render('add_attendance.html.twig', [
        'students' => $students,
        'errors' => $errors,
        'message' => $message,
        'today' => date('Y-m-d'),
        'csrf_token' => CSRFToken::generate(),
        'csrf_token_name' => CSRFToken::name(),
        'old_attendance_date' => $_POST['attendance_date'] ?? date('Y-m-d'),
        'old_status' => $_POST['status'] ?? '',
        'old_remarks' => $_POST['remarks'] ?? '',
    ]);
    
} catch (Exception $e) {
    error_log('Error in add_attendance: ' . $e->getMessage());
    echo '<div class="alert alert-danger">An error occurred while processing your request.</div>';
}
