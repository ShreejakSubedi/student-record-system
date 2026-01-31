<?php
/**
 * Add Student Page
 * Form for adding a new student
 */

declare(strict_types=1);

require_once '../config/db.php';
require_once '../config/twig.php';
require_once '../config/csrf.php';
// Authentication check
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /fullstack2/student-record-system/');
    exit;
}

use App\Models\Student;
use App\Controllers\StudentController;

$errors = [];
$message = '';

try {
    $studentModel = new Student($pdo);
    $controller = new StudentController($studentModel, new \App\Models\Grade($pdo), new \App\Models\Attendance($pdo));
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST[CSRFToken::name()] ?? '';
        if (!CSRFToken::verify($token)) {
            throw new Exception('Invalid request (CSRF token mismatch).');
        }

        $result = $controller->createStudent($_POST);

        if ($result['success']) {
            header('Location: index.php?message=' . urlencode($result['message']));
            exit;
        } else {
            $errors = $result['errors'] ?? [];
            $message = $result['message'] ?? '';
        }
    }
    
    // Render template
    echo $twig->render('add_student.html.twig', [
        'errors' => $errors,
        'message' => $message,
        'today' => date('Y-m-d'),
        'csrf_token' => CSRFToken::generate(),
        'csrf_token_name' => CSRFToken::name(),
        'old_roll_number' => $_POST['roll_number'] ?? '',
        'old_first_name' => $_POST['first_name'] ?? '',
        'old_last_name' => $_POST['last_name'] ?? '',
        'old_email' => $_POST['email'] ?? '',
        'old_phone' => $_POST['phone'] ?? '',
        'old_gender' => $_POST['gender'] ?? '',
        'old_class' => $_POST['class'] ?? '',
        'old_address' => $_POST['address'] ?? '',
        'old_enrollment_date' => $_POST['enrollment_date'] ?? date('Y-m-d'),
        'old_status' => $_POST['status'] ?? 'Active',
        'old_dob' => $_POST['date_of_birth'] ?? '',
    ]);
    
} catch (Exception $e) {
    error_log('Error in add_student: ' . $e->getMessage());
    echo '<div class="alert alert-danger">An error occurred while processing your request.</div>';
}
