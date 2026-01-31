<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$username = 'admin';
$email = 'admin@local';
$password = 'admin123';

try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "Admin already exists.\n";
        exit(0);
    }

    // Use ARGON2ID if available (PHP 7.4+), fallback to DEFAULT
    $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
    $hash = password_hash($password, $algo);
    
    $ins = $pdo->prepare('INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())');
    $ins->execute([':username' => $username, ':email' => $email, ':password' => $hash]);
    echo "Admin user created successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
