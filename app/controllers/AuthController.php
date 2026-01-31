<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private User $userModel;
    
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    
    public function login(string $username, string $password): array
    {
        $username = trim($username);
        $password = trim($password);
        
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Username and password required.'];
        }
        
        $user = $this->userModel->findByUsername($username);
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }
        
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }
        
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        
        return ['success' => true, 'message' => 'Login successful.'];
    }
    
    public function signup(string $username, string $email, string $password, string $password_confirm): array
    {
        $username = trim($username);
        $email = trim($email);
        
        $errors = [];
        
        if (empty($username) || strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }
        if ($password !== $password_confirm) {
            $errors[] = 'Passwords do not match.';
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        if ($this->userModel->findByUsername($username)) {
            return ['success' => false, 'errors' => ['Username already taken.']];
        }
        if ($this->userModel->findByEmail($email)) {
            return ['success' => false, 'errors' => ['Email already registered.']];
        }
        
        $result = $this->userModel->create($username, $email, $password);
        if ($result['success']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $result['id'];
            return ['success' => true, 'message' => 'Account created. You are now logged in.'];
        }
        
        return ['success' => false, 'errors' => ['Unable to create account.']];
    }
    
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }
}
