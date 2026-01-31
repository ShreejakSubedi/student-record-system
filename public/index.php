<?php
/**
 * Main Dashboard Page
 * Displays all students, grades, and attendance records
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

use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Controllers\StudentController;

try {
    // Initialize models
    $studentModel = new Student($pdo);
    $gradeModel = new Grade($pdo);
    $attendanceModel = new Attendance($pdo);
    
    // Initialize controller
    $controller = new StudentController($studentModel, $gradeModel, $attendanceModel);
    
    // Get data
    $students = $controller->getAllWithSummary();
    $grades = $gradeModel->getAll();
    $attendance = $attendanceModel->getAll();
    $activeStudents = count(array_filter($students, fn($s) => $s['status'] === 'Active'));
    
    // Render template
    echo $twig->render('home.html.twig', [
        'students' => $students,
        'grades' => $grades,
        'attendance' => $attendance,
        'active_students' => $activeStudents,
    ]);
    
} catch (Exception $e) {
    error_log('Error in dashboard: ' . $e->getMessage());
    echo '<div class="alert alert-danger">An error occurred while loading the dashboard.</div>';
}
