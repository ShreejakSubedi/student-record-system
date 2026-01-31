<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/twig.php';
require_once __DIR__ . '/../config/csrf.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;
use App\Controllers\AuthController;

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    $token = $_POST[CSRFToken::name()] ?? '';
    if (!CSRFToken::verify($token)) {
        $errors[] = 'Invalid request (CSRF token mismatch).';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        try {
            $userModel = new User($pdo);
            $authController = new AuthController($userModel);
            
            $result = $authController->signup($username, $email, $password, $password_confirm);
            if ($result['success']) {
                header('Location: /fullstack2/student-record-system/public/index.php');
                exit;
            }
            $errors = $result['errors'] ?? [];
        } catch (Throwable $e) {
            error_log('Signup error: ' . $e->getMessage());
            $errors[] = 'An error occurred during signup.';
        }
    }
}

echo $twig->render('auth/signup.html.twig', [
    'username' => $username,
    'email' => $email,
    'errors' => $errors,
    'csrf_token' => CSRFToken::generate(),
    'csrf_token_name' => CSRFToken::name(),
]);
