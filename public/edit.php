<?php
/**
 * Edit Student Page
 * Form for editing student information
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
use App\Controllers\StudentController;

$errors = [];
$message = '';
$student = null;

try {
    $studentModel = new Student($pdo);
    $controller = new StudentController($studentModel, new \App\Models\Grade($pdo), new \App\Models\Attendance($pdo));
    
    // Get student ID from URL
    $studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$studentId || !($student = $studentModel->getById($studentId))) {
        throw new Exception('Student not found');
    }
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST[CSRFToken::name()] ?? '';
        if (!CSRFToken::verify($token)) {
            throw new Exception('Invalid request (CSRF token mismatch).');
        }

        $result = $controller->updateStudent($studentId, $_POST);

        if ($result['success']) {
            header('Location: /fullstack2/student-record-system/public/index.php?message=' . urlencode($result['message']));
            exit;
        } else {
            $errors = $result['errors'] ?? [];
            $message = $result['message'] ?? '';
        }
    }
    
    // Render template
    echo $twig->render('edit_student.html.twig', [
        'student' => $student,
        'errors' => $errors,
        'message' => $message,
        'csrf_token' => CSRFToken::generate(),
        'csrf_token_name' => CSRFToken::name(),
    ]);
    
} catch (Exception $e) {
    error_log('Error in edit_student: ' . $e->getMessage());
    echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
}
