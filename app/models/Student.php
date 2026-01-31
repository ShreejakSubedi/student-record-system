<?php
/**
 * Student Model
 * Handles all student-related database operations
 */

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Student
{
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all students
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM students 
                ORDER BY first_name ASC, last_name ASC
            ');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching students: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get single student by ID
     */
    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM students WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching student: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Search students by name or email
     */
    public function search(string $query): array
    {
        try {
            $searchTerm = '%' . $query . '%';
            $stmt = $this->pdo->prepare('
                SELECT id, roll_number, CONCAT(first_name, " ", last_name) as full_name, 
                       email, class, status
                FROM students 
                WHERE first_name LIKE :query 
                   OR last_name LIKE :query 
                   OR email LIKE :query
                   OR roll_number LIKE :query
                ORDER BY first_name ASC
                LIMIT 20
            ');
            $stmt->execute([':query' => $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error searching students: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create a new student
     */
    public function create(array $data): bool
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO students 
                (roll_number, first_name, last_name, email, phone, date_of_birth, 
                 gender, address, class, enrollment_date, status)
                VALUES 
                (:roll_number, :first_name, :last_name, :email, :phone, :dob, 
                 :gender, :address, :class, :enrollment_date, :status)
            ');
            
            return $stmt->execute([
                ':roll_number' => $data['roll_number'] ?? null,
                ':first_name' => $data['first_name'] ?? null,
                ':last_name' => $data['last_name'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':dob' => $data['date_of_birth'] ?? null,
                ':gender' => $data['gender'] ?? null,
                ':address' => $data['address'] ?? null,
                ':class' => $data['class'] ?? null,
                ':enrollment_date' => $data['enrollment_date'] ?? date('Y-m-d'),
                ':status' => $data['status'] ?? 'Active',
            ]);
        } catch (PDOException $e) {
            error_log('Error creating student: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update student
     */
    public function update(int $id, array $data): bool
    {
        try {
            $stmt = $this->pdo->prepare('
                UPDATE students 
                SET first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    phone = :phone,
                    date_of_birth = :dob,
                    gender = :gender,
                    address = :address,
                    class = :class,
                    status = :status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            
            return $stmt->execute([
                ':id' => $id,
                ':first_name' => $data['first_name'] ?? null,
                ':last_name' => $data['last_name'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':dob' => $data['date_of_birth'] ?? null,
                ':gender' => $data['gender'] ?? null,
                ':address' => $data['address'] ?? null,
                ':class' => $data['class'] ?? null,
                ':status' => $data['status'] ?? 'Active',
            ]);
        } catch (PDOException $e) {
            error_log('Error updating student: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete student
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM students WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Error deleting student: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email exists
     */
    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        try {
            if ($exceptId) {
                $stmt = $this->pdo->prepare('SELECT id FROM students WHERE email = :email AND id != :id');
                $stmt->execute([':email' => $email, ':id' => $exceptId]);
            } else {
                $stmt = $this->pdo->prepare('SELECT id FROM students WHERE email = :email');
                $stmt->execute([':email' => $email]);
            }
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Error checking email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if roll number exists
     */
    public function rollNumberExists(string $rollNumber, ?int $exceptId = null): bool
    {
        try {
            if ($exceptId) {
                $stmt = $this->pdo->prepare('SELECT id FROM students WHERE roll_number = :roll_number AND id != :id');
                $stmt->execute([':roll_number' => $rollNumber, ':id' => $exceptId]);
            } else {
                $stmt = $this->pdo->prepare('SELECT id FROM students WHERE roll_number = :roll_number');
                $stmt->execute([':roll_number' => $rollNumber]);
            }
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Error checking roll number: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get students by class
     */
    public function getByClass(string $class): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM students 
                WHERE class = :class 
                ORDER BY first_name ASC, last_name ASC
            ');
            $stmt->execute([':class' => $class]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching students by class: ' . $e->getMessage());
            return [];
        }
    }
}
