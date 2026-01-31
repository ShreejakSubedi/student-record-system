<?php
/**
 * Grade Model
 * Handles all grade-related database operations
 */

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Grade
{
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all grades
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT g.*, s.roll_number, CONCAT(s.first_name, " ", s.last_name) as student_name
                FROM grades g
                JOIN students s ON g.student_id = s.id
                ORDER BY g.exam_date DESC, s.first_name ASC
            ');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching grades: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get grades for a specific student
     */
    public function getByStudentId(int $studentId): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM grades 
                WHERE student_id = :student_id
                ORDER BY exam_date DESC
            ');
            $stmt->execute([':student_id' => $studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching student grades: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get single grade record
     */
    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT g.*, s.roll_number, CONCAT(s.first_name, " ", s.last_name) as student_name
                FROM grades g
                JOIN students s ON g.student_id = s.id
                WHERE g.id = :id
            ');
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching grade: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create a new grade record
     */
    public function create(array $data): bool
    {
        try {
            // Calculate grade based on percentage
            $percentage = ($data['marks_obtained'] / $data['total_marks']) * 100;
            $grade = $this->calculateGrade((float)$percentage);
            
            $stmt = $this->pdo->prepare('
                INSERT INTO grades 
                (student_id, subject, semester, marks_obtained, total_marks, grade, percentage, exam_date)
                VALUES 
                (:student_id, :subject, :semester, :marks_obtained, :total_marks, :grade, :percentage, :exam_date)
            ');
            
            return $stmt->execute([
                ':student_id' => $data['student_id'] ?? null,
                ':subject' => $data['subject'] ?? null,
                ':semester' => $data['semester'] ?? null,
                ':marks_obtained' => $data['marks_obtained'] ?? null,
                ':total_marks' => $data['total_marks'] ?? 100,
                ':grade' => $grade,
                ':percentage' => $percentage,
                ':exam_date' => $data['exam_date'] ?? date('Y-m-d'),
            ]);
        } catch (PDOException $e) {
            error_log('Error creating grade: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update grade record
     */
    public function update(int $id, array $data): bool
    {
        try {
            $percentage = ($data['marks_obtained'] / $data['total_marks']) * 100;
            $grade = $this->calculateGrade((float)$percentage);
            
            $stmt = $this->pdo->prepare('
                UPDATE grades 
                SET subject = :subject,
                    semester = :semester,
                    marks_obtained = :marks_obtained,
                    total_marks = :total_marks,
                    grade = :grade,
                    percentage = :percentage,
                    exam_date = :exam_date,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            
            return $stmt->execute([
                ':id' => $id,
                ':subject' => $data['subject'] ?? null,
                ':semester' => $data['semester'] ?? null,
                ':marks_obtained' => $data['marks_obtained'] ?? null,
                ':total_marks' => $data['total_marks'] ?? 100,
                ':grade' => $grade,
                ':percentage' => $percentage,
                ':exam_date' => $data['exam_date'] ?? date('Y-m-d'),
            ]);
        } catch (PDOException $e) {
            error_log('Error updating grade: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete grade record
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM grades WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Error deleting grade: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Calculate letter grade from percentage
     */
    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 95) return 'A+';
        if ($percentage >= 90) return 'A';
        if ($percentage >= 85) return 'B+';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 75) return 'B-';
        if ($percentage >= 70) return 'C+';
        if ($percentage >= 65) return 'C';
        if ($percentage >= 60) return 'C-';
        if ($percentage >= 55) return 'D+';
        if ($percentage >= 50) return 'D';
        return 'F';
    }
    
    /**
     * Get grades by subject
     */
    public function getBySubject(string $subject): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT g.*, s.roll_number, CONCAT(s.first_name, " ", s.last_name) as student_name
                FROM grades g
                JOIN students s ON g.student_id = s.id
                WHERE g.subject = :subject
                ORDER BY g.percentage DESC
            ');
            $stmt->execute([':subject' => $subject]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching grades by subject: ' . $e->getMessage());
            return [];
        }
    }
}
