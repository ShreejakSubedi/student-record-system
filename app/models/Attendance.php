<?php
/**
 * Attendance Model
 * Handles all attendance-related database operations
 */

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Attendance
{
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all attendance records
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT a.*, s.roll_number, CONCAT(s.first_name, " ", s.last_name) as student_name
                FROM attendance a
                JOIN students s ON a.student_id = s.id
                ORDER BY a.attendance_date DESC, s.first_name ASC
            ');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching attendance records: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get attendance records for a specific student
     */
    public function getByStudentId(int $studentId): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM attendance 
                WHERE student_id = :student_id
                ORDER BY attendance_date DESC
            ');
            $stmt->execute([':student_id' => $studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching student attendance: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get single attendance record
     */
    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT a.*, s.roll_number, CONCAT(s.first_name, " ", s.last_name) as student_name
                FROM attendance a
                JOIN students s ON a.student_id = s.id
                WHERE a.id = :id
            ');
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching attendance record: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create a new attendance record
     */
    public function create(array $data): bool
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO attendance 
                (student_id, attendance_date, status, remarks)
                VALUES 
                (:student_id, :attendance_date, :status, :remarks)
                ON DUPLICATE KEY UPDATE
                status = :status,
                remarks = :remarks,
                updated_at = CURRENT_TIMESTAMP
            ');
            
            return $stmt->execute([
                ':student_id' => $data['student_id'] ?? null,
                ':attendance_date' => $data['attendance_date'] ?? date('Y-m-d'),
                ':status' => $data['status'] ?? 'Present',
                ':remarks' => $data['remarks'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log('Error creating attendance record: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update attendance record
     */
    public function update(int $id, array $data): bool
    {
        try {
            $stmt = $this->pdo->prepare('
                UPDATE attendance 
                SET status = :status,
                    remarks = :remarks,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            
            return $stmt->execute([
                ':id' => $id,
                ':status' => $data['status'] ?? 'Present',
                ':remarks' => $data['remarks'] ?? null,
            ]);
        } catch (PDOException $e) {
            error_log('Error updating attendance record: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete attendance record
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM attendance WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Error deleting attendance record: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get attendance percentage for a student
     */
    public function getAttendancePercentage(int $studentId): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT 
                    COUNT(CASE WHEN status = "Present" THEN 1 END) as present_days,
                    COUNT(CASE WHEN status = "Absent" THEN 1 END) as absent_days,
                    COUNT(CASE WHEN status = "Late" THEN 1 END) as late_days,
                    COUNT(id) as total_days,
                    ROUND((COUNT(CASE WHEN status = "Present" THEN 1 END) / COUNT(id)) * 100, 2) as attendance_percentage
                FROM attendance 
                WHERE student_id = :student_id
            ');
            $stmt->execute([':student_id' => $studentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log('Error calculating attendance percentage: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get attendance records for a specific date
     */
    public function getByDate(string $date): array
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT a.*, s.roll_number, CONCAT(s.first_name, " ", s.last_name) as student_name
                FROM attendance a
                JOIN students s ON a.student_id = s.id
                WHERE a.attendance_date = :date
                ORDER BY s.first_name ASC
            ');
            $stmt->execute([':date' => $date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching attendance by date: ' . $e->getMessage());
            return [];
        }
    }
}
