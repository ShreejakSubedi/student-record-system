<?php
/**
 * Add Grade Page
 * Form for adding grades
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
use App\Models\Grade;
use App\Controllers\GradeController;

$errors = [];
$message = '';

try {
    $studentModel = new Student($pdo);
    $gradeModel = new Grade($pdo);
    $controller = new GradeController($gradeModel, $studentModel);
    
    // Get all students for dropdown
    $students = $studentModel->getAll();
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST[CSRFToken::name()] ?? '';
        if (!CSRFToken::verify($token)) {
            throw new Exception('Invalid request (CSRF token mismatch).');
        }

        $result = $controller->createGrade($_POST);

        if ($result['success']) {
            header('Location: index.php?message=' . urlencode($result['message']));
            exit;
        } else {
            $errors = $result['errors'] ?? [];
            $message = $result['message'] ?? '';
        }
    }
    
    // Render template
    echo $twig->render('add_grade.html.twig', [
        'students' => $students,
        'errors' => $errors,
        'message' => $message,
        'today' => date('Y-m-d'),
        'csrf_token' => CSRFToken::generate(),
        'csrf_token_name' => CSRFToken::name(),
        'old_subject' => $_POST['subject'] ?? '',
        'old_semester' => $_POST['semester'] ?? '',
        'old_marks_obtained' => $_POST['marks_obtained'] ?? '',
        'old_total_marks' => $_POST['total_marks'] ?? 100,
        'old_exam_date' => $_POST['exam_date'] ?? date('Y-m-d'),
        'old_percentage' => $_POST['percentage'] ?? '',
        'old_grade' => $_POST['grade'] ?? '',
    ]);
    
} catch (Exception $e) {
    error_log('Error in add_grade: ' . $e->getMessage());
    echo '<div class="alert alert-danger">An error occurred while processing your request.</div>';
}
