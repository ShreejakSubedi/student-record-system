<?php
/**
 * Grade Controller
 * Handles grade-related business logic
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Grade;
use App\Models\Student;

class GradeController
{
    private Grade $gradeModel;
    private Student $studentModel;
    
    public function __construct(Grade $gradeModel, Student $studentModel)
    {
        $this->gradeModel = $gradeModel;
        $this->studentModel = $studentModel;
    }
    
    /**
     * Get all grades with student details
     */
    public function getAllGrades(): array
    {
        return $this->gradeModel->getAll();
    }
    
    /**
     * Get grades for a student
     */
    public function getStudentGrades(int $studentId): array
    {
        if (!$this->studentModel->getById($studentId)) {
            return [];
        }
        
        return $this->gradeModel->getByStudentId($studentId);
    }
    
    /**
     * Validate grade data
     */
    public function validateGradeData(array $data): array
    {
        $errors = [];
        
        // Validate student
        if (empty($data['student_id'])) {
            $errors['student_id'] = 'Student is required';
        } elseif (!$this->studentModel->getById((int)$data['student_id'])) {
            $errors['student_id'] = 'Student not found';
        }
        
        // Validate subject
        if (empty($data['subject'])) {
            $errors['subject'] = 'Subject is required';
        }
        
        // Validate marks
        if (empty($data['marks_obtained'])) {
            $errors['marks_obtained'] = 'Marks obtained is required';
        } elseif (!is_numeric($data['marks_obtained'])) {
            $errors['marks_obtained'] = 'Marks must be a number';
        } elseif ((float)$data['marks_obtained'] < 0) {
            $errors['marks_obtained'] = 'Marks cannot be negative';
        }
        
        // Validate total marks
        $totalMarks = $data['total_marks'] ?? 100;
        if (!is_numeric($totalMarks)) {
            $errors['total_marks'] = 'Total marks must be a number';
        } elseif ((float)$data['marks_obtained'] > (float)$totalMarks) {
            $errors['marks_obtained'] = 'Marks obtained cannot exceed total marks';
        }
        
        return $errors;
    }
    
    /**
     * Create new grade
     */
    public function createGrade(array $data): array
    {
        $errors = $this->validateGradeData($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $sanitizedData = [
                'student_id' => (int)$data['student_id'],
                'subject' => htmlspecialchars($data['subject']),
                'semester' => htmlspecialchars($data['semester'] ?? ''),
                'marks_obtained' => (float)$data['marks_obtained'],
                'total_marks' => (float)($data['total_marks'] ?? 100),
                'exam_date' => $data['exam_date'] ?? date('Y-m-d'),
            ];
            
            if ($this->gradeModel->create($sanitizedData)) {
                return ['success' => true, 'message' => 'Grade added successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to add grade'];
            }
        } catch (\Exception $e) {
            error_log('Error creating grade: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while creating grade'];
        }
    }
    
    /**
     * Update grade
     */
    public function updateGrade(int $gradeId, array $data): array
    {
        if (!$this->gradeModel->getById($gradeId)) {
            return ['success' => false, 'message' => 'Grade not found'];
        }
        
        $errors = $this->validateGradeData($data);
        
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        try {
            $sanitizedData = [
                'subject' => htmlspecialchars($data['subject']),
                'semester' => htmlspecialchars($data['semester'] ?? ''),
                'marks_obtained' => (float)$data['marks_obtained'],
                'total_marks' => (float)($data['total_marks'] ?? 100),
                'exam_date' => $data['exam_date'] ?? date('Y-m-d'),
            ];
            
            if ($this->gradeModel->update($gradeId, $sanitizedData)) {
                return ['success' => true, 'message' => 'Grade updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update grade'];
            }
        } catch (\Exception $e) {
            error_log('Error updating grade: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while updating grade'];
        }
    }
    
    /**
     * Delete grade
     */
    public function deleteGrade(int $gradeId): array
    {
        if (!$this->gradeModel->getById($gradeId)) {
            return ['success' => false, 'message' => 'Grade not found'];
        }
        
        try {
            if ($this->gradeModel->delete($gradeId)) {
                return ['success' => true, 'message' => 'Grade deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete grade'];
            }
        } catch (\Exception $e) {
            error_log('Error deleting grade: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred while deleting grade'];
        }
    }
}
