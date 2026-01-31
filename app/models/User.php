<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(string $username, string $email, string $password): array
    {
        // Use ARGON2ID if available (PHP 7.4+), fallback to DEFAULT
        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
        $hash = password_hash($password, $algo);
        $stmt = $this->pdo->prepare('INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())');
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hash]);
        return ['success' => true, 'id' => (int)$this->pdo->lastInsertId()];
    }

    public function verifyPassword(string $username, string $password): bool
    {
        $user = $this->findByUsername($username);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }
}
