<?php
/**
 * Student Controller
 * Handles student-related business logic
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;

class StudentController
{
    private Student $studentModel;
    private Grade $gradeModel;
    private Attendance $attendanceModel;
    
    public function __construct(Student $studentModel, Grade $gradeModel, Attendance $attendanceModel)
    {
        $this->studentModel = $studentModel;
        $this->gradeModel = $gradeModel;
        $this->attendanceModel = $attendanceModel;
    }
    
    /**
     * Get all students with performance summary
     */
    public function getAllWithSummary(): array
    {
        $students = $this->studentModel->getAll();
        
        foreach ($students as &$student) {
            $grades = $this->gradeModel->getByStudentId($student['id']);
            $attendance = $this->attendanceModel->getAttendancePercentage($student['id']);
            
            $student['average_grade'] = 0;
            if (!empty($grades)) {
                $totalPercentage = array_sum(array_column($grades, 'percentage'));
                $student['average_grade'] = round($totalPercentage / count($grades), 2);
            }
            
            $student['attendance_percentage'] = $attendance['attendance_percentage'] ?? 0;
        }
        
        return $students;
    }
    
    /**
     * Get student details with related records
     */
    public function getStudentDetails(int $studentId): array
    {
        $student = $this->studentModel->getById($studentId);
        
        if (!$student) {
            return [];
        }
        
        $student['grades'] = $this->gradeModel->getByStudentId($studentId);
        $student['attendance'] = $this->attendanceModel->getByStudentId($studentId);
        $student['attendance_summary'] = $this->attendanceModel->getAttendancePercentage($studentId);
        
        return $student;
    }
    
    /**
     * Validate student data
     */
    public function validateStudentData(array $data, ?int $studentId = null): array
    {
        $errors = [];
        
        // Validate roll number
        if (empty($data['roll_number'])) {
            $errors['roll_number'] = 'Roll number is required';
        } elseif ($this->studentModel->rollNumberExists($data['roll_number'], $studentId)) {
            $errors['roll_number'] = 'This roll number already exists';
        }
        
        // Validate first name
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        } elseif (strlen($data['first_name']) < 2) {
            $errors['first_name'] = 'First name must be at least 2 characters';
        }
        
        // Validate last name
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        } elseif (strlen($data['last_name']) < 2) {
            $errors['last_name'] = 'Last name must be at least 2 characters';
        }
        
        // Validate email
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif ($this->studentModel->emailExists($data['email'], $studentId)) {
            $errors['email'] = 'This email already exists';
        }
        
        // Validate phone (optional)
        if (!empty($data['phone']) && !preg_match('/^[0-9\-\+\s]{10,}$/', $data['phone'])) {
            $errors['phone'] = 'Invalid phone number format';
        }
        
        // Validate class
        if (empty($data['class'])) {
            $errors['class'] = 'Class is required';
        }
        
        return $errors;
    }
    
    /**
     * Create new student
     */
    public function createStudent(array $data): array
    {
        $errors = $this->validateStudentData($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            // Sanitize input
            $sanitizedData = [
                'roll_number' => htmlspecialchars($data['roll_number']),
                'first_name' => htmlspecialchars($data['first_name']),
                'last_name' => htmlspecialchars($data['last_name']),
                'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
                'phone' => htmlspecialchars($data['phone'] ?? ''),
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => htmlspecialchars($data['address'] ?? ''),
                'class' => htmlspecialchars($data['class']),
                'enrollment_date' => $data['enrollment_date'] ?? date('Y-m-d'),
                'status' => $data['status'] ?? 'Active',
            ];
            
            if ($this->studentModel->create($sanitizedData)) {
                return ['success' => true, 'message' => 'Student added successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to add student'];
            }
        } catch (\Exception $e) {
            error_log('Error creating student: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while creating student'];
        }
    }
    
    /**
     * Update student
     */
    public function updateStudent(int $studentId, array $data): array
    {
        if (!$this->studentModel->getById($studentId)) {
            return ['success' => false, 'message' => 'Student not found'];
        }
        
        $errors = $this->validateStudentData($data, $studentId);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $sanitizedData = [
                'roll_number' => htmlspecialchars($data['roll_number']),
                'first_name' => htmlspecialchars($data['first_name']),
                'last_name' => htmlspecialchars($data['last_name']),
                'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
                'phone' => htmlspecialchars($data['phone'] ?? ''),
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'address' => htmlspecialchars($data['address'] ?? ''),
                'class' => htmlspecialchars($data['class']),
                'status' => $data['status'] ?? 'Active',
            ];
            
            if ($this->studentModel->update($studentId, $sanitizedData)) {
                return ['success' => true, 'message' => 'Student updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update student'];
            }
        } catch (\Exception $e) {
            error_log('Error updating student: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while updating student'];
        }
    }
    
    /**
     * Delete student
     */
    public function deleteStudent(int $studentId): array
    {
        if (!$this->studentModel->getById($studentId)) {
            return ['success' => false, 'message' => 'Student not found'];
        }
        
        try {
            if ($this->studentModel->delete($studentId)) {
                return ['success' => true, 'message' => 'Student deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete student'];
            }
        } catch (\Exception $e) {
            error_log('Error deleting student: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while deleting student'];
        }
    }
    
    /**
     * Search students
     */
    public function searchStudents(string $query): array
    {
        if (strlen($query) < 2) {
            return [];
        }
        
        return $this->studentModel->search(htmlspecialchars($query));
    }
}
