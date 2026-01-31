<?php
/**
 * Attendance Controller
 * Handles attendance-related business logic
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Attendance;
use App\Models\Student;

class AttendanceController
{
    private Attendance $attendanceModel;
    private Student $studentModel;
    
    public function __construct(Attendance $attendanceModel, Student $studentModel)
    {
        $this->attendanceModel = $attendanceModel;
        $this->studentModel = $studentModel;
    }
    
    /**
     * Get all attendance records
     */
    public function getAllAttendance(): array
    {
        return $this->attendanceModel->getAll();
    }
    
    /**
     * Get attendance for a student
     */
    public function getStudentAttendance(int $studentId): array
    {
        if (!$this->studentModel->getById($studentId)) {
            return [];
        }
        
        return $this->attendanceModel->getByStudentId($studentId);
    }
    
    /**
     * Validate attendance data
     */
    public function validateAttendanceData(array $data): array
    {
        $errors = [];
        
        // Validate student
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Student is required';
        } elseif (!$this->studentModel->getById((int)$data['student_id'])) {
            $errors['student_id'] = 'Student not found';
        }
        
        // Validate date
        if (empty($data['attendance_date'])) {
            $errors['attendance_date'] = 'Attendance date is required';
        } else {
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['attendance_date'])) {
                $errors['attendance_date'] = 'Invalid date format (use YYYY-MM-DD)';
            }
        }
        
        // Validate status
        if (empty($data['status'])) {
            $errors['status'] = 'Status is required';
        } elseif (!in_array($data['status'], ['Present', 'Absent', 'Late'])) {
            $errors['status'] = 'Invalid status';
        }
        
        return $errors;
    }
    
    /**
     * Create new attendance record
     */
    public function createAttendance(array $data): array
    {
        $errors = $this->validateAttendanceData($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $sanitizedData = [
                'student_id' => (int)$data['student_id'],
                'attendance_date' => $data['attendance_date'],
                'status' => htmlspecialchars($data['status']),
                'remarks' => htmlspecialchars($data['remarks'] ?? ''),
            ];
            
            if ($this->attendanceModel->create($sanitizedData)) {
                return ['success' => true, 'message' => 'Attendance record added successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to add attendance record'];
            }
        } catch (\Exception $e) {
            error_log('Error creating attendance record: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while creating attendance record'];
        }
    }
    
    /**
     * Update attendance record
     */
    public function updateAttendance(int $attendanceId, array $data): array
    {
        if (!$this->attendanceModel->getById($attendanceId)) {
            return ['success' => false, 'message' => 'Attendance record not found'];
        }
        
        $errors = [];
        
        // Validate status
        if (empty($data['status'])) {
            $errors['status'] = 'Status is required';
        } elseif (!in_array($data['status'], ['Present', 'Absent', 'Late'])) {
            $errors['status'] = 'Invalid status';
        }
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $sanitizedData = [
                'status' => htmlspecialchars($data['status']),
                'remarks' => htmlspecialchars($data['remarks'] ?? ''),
            ];
            
            if ($this->attendanceModel->update($attendanceId, $sanitizedData)) {
                return ['success' => true, 'message' => 'Attendance record updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update attendance record'];
            }
        } catch (\Exception $e) {
            error_log('Error updating attendance record: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while updating attendance record'];
        }
    }
    
    /**
     * Delete attendance record
     */
    public function deleteAttendance(int $attendanceId): array
    {
        if (!$this->attendanceModel->getById($attendanceId)) {
            return ['success' => false, 'message' => 'Attendance record not found'];
        }
        
        try {
            if ($this->attendanceModel->delete($attendanceId)) {
                return ['success' => true, 'message' => 'Attendance record deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete attendance record'];
            }
        } catch (\Exception $e) {
            error_log('Error deleting attendance record: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while deleting attendance record'];
        }
    }
    
    /**
     * Get attendance summary for a student
     */
    public function getAttendanceSummary(int $studentId): array
    {
        if (!$this->studentModel->getById($studentId)) {
            return [];
        }
        
        return $this->attendanceModel->getAttendancePercentage($studentId);
    }
}
