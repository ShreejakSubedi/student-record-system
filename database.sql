-- ============================================
-- Student Record Management System Database
-- ============================================

-- Create database
DROP DATABASE IF EXISTS `student_records`;
CREATE DATABASE `student_records` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `student_records`;

-- ============================================
-- Students Table
-- ============================================
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `roll_number` VARCHAR(50) NOT NULL UNIQUE,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20),
  `date_of_birth` DATE,
  `gender` ENUM('Male', 'Female', 'Other'),
  `address` TEXT,
  `class` VARCHAR(50),
  `enrollment_date` DATE NOT NULL DEFAULT CURRENT_DATE,
  `status` ENUM('Active', 'Inactive', 'Graduated') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Indexes for performance
  INDEX `idx_roll_number` (`roll_number`),
  INDEX `idx_email` (`email`),
  INDEX `idx_class` (`class`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Grades Table
-- ============================================
DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `semester` VARCHAR(20),
  `marks_obtained` DECIMAL(5, 2),
  `total_marks` DECIMAL(5, 2) DEFAULT 100,
  `grade` VARCHAR(2),
  `percentage` DECIMAL(5, 2),
  `exam_date` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign key constraint with CASCADE delete
  CONSTRAINT `fk_grades_students` FOREIGN KEY (`student_id`) 
    REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  
  -- Indexes for performance
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_subject` (`subject`),
  INDEX `idx_semester` (`semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Attendance Table
-- ============================================
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `attendance_date` DATE NOT NULL,
  `status` ENUM('Present', 'Absent', 'Late') DEFAULT 'Present',
  `remarks` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign key constraint with CASCADE delete
  CONSTRAINT `fk_attendance_students` FOREIGN KEY (`student_id`) 
    REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  
  -- Indexes for performance
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_attendance_date` (`attendance_date`),
  UNIQUE KEY `unique_attendance` (`student_id`, `attendance_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SAMPLE DATA
-- ============================================

-- Insert sample students
INSERT INTO `students` (`roll_number`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `class`, `enrollment_date`, `status`) VALUES
('STU001', 'Pratik', 'Luitel', 'pratik.luitel@school.com', '9841234567', '2003-05-15', 'Male', '123 Main Street, Kathmandu', 'Class A', '2023-08-01', 'Active'),
('STU002', 'Isha', 'Sharma', 'isha.sharma@school.com', '9842234567', '2004-03-22', 'Female', '456 Park Avenue, Lalitpur', 'Class A', '2023-08-01', 'Active'),
('STU003', 'Ravi', 'Kumar', 'ravi.kumar@school.com', '9843234567', '2003-11-10', 'Male', '789 Oak Street, Bhaktapur', 'Class B', '2023-08-01', 'Active'),
('STU004', 'Neha', 'Singh', 'neha.singh@school.com', '9844234567', '2004-01-28', 'Female', '321 Elm Street, Kathmandu', 'Class B', '2023-08-01', 'Active'),
('STU005', 'Amit', 'Patel', 'amit.patel@school.com', '9845234567', '2003-07-14', 'Male', '654 Pine Street, Lalitpur', 'Class A', '2023-08-01', 'Active'),
('STU006', 'Priya', 'Verma', 'priya.verma@school.com', '9846234567', '2004-04-03', 'Female', '987 Maple Street, Bhaktapur', 'Class C', '2023-08-01', 'Active'),
('STU007', 'Vikram', 'Singh', 'vikram.singh@school.com', '9847234567', '2003-09-19', 'Male', '147 Cedar Street, Kathmandu', 'Class B', '2023-08-01', 'Inactive'),
('STU008', 'Divya', 'Nair', 'divya.nair@school.com', '9848234567', '2004-02-11', 'Female', '258 Birch Street, Lalitpur', 'Class A', '2023-08-01', 'Active'),
('STU009', 'Arjun', 'Reddy', 'arjun.reddy@school.com', '9849234567', '2003-12-05', 'Male', '369 Spruce Street, Bhaktapur', 'Class C', '2023-08-01', 'Active'),
('STU010', 'Aadhya', 'Gupta', 'aadhya.gupta@school.com', '9850234567', '2004-06-17', 'Female', '741 Ash Street, Kathmandu', 'Class B', '2023-08-01', 'Active');

-- Insert sample grades
INSERT INTO `grades` (`student_id`, `subject`, `semester`, `marks_obtained`, `total_marks`, `grade`, `percentage`, `exam_date`) VALUES
(1, 'Mathematics', 'Semester 1', 88, 100, 'A', 88.00, '2023-12-15'),
(1, 'English', 'Semester 1', 82, 100, 'B+', 82.00, '2023-12-18'),
(1, 'Science', 'Semester 1', 90, 100, 'A', 90.00, '2023-12-20'),
(2, 'Mathematics', 'Semester 1', 92, 100, 'A', 92.00, '2023-12-15'),
(2, 'English', 'Semester 1', 88, 100, 'A', 88.00, '2023-12-18'),
(2, 'Science', 'Semester 1', 85, 100, 'B+', 85.00, '2023-12-20'),
(3, 'Mathematics', 'Semester 1', 75, 100, 'B', 75.00, '2023-12-15'),
(3, 'English', 'Semester 1', 78, 100, 'B', 78.00, '2023-12-18'),
(3, 'Science', 'Semester 1', 80, 100, 'B', 80.00, '2023-12-20'),
(4, 'Mathematics', 'Semester 1', 95, 100, 'A+', 95.00, '2023-12-15'),
(5, 'English', 'Semester 1', 87, 100, 'A', 87.00, '2023-12-18'),
(6, 'Science', 'Semester 1', 72, 100, 'B', 72.00, '2023-12-20');

-- Insert sample attendance
INSERT INTO `attendance` (`student_id`, `attendance_date`, `status`, `remarks`) VALUES
(1, '2024-01-10', 'Present', 'On time'),
(1, '2024-01-11', 'Present', 'On time'),
(1, '2024-01-12', 'Late', 'Arrived 10 minutes late'),
(1, '2024-01-13', 'Present', 'On time'),
(2, '2024-01-10', 'Present', 'On time'),
(2, '2024-01-11', 'Absent', 'Sick leave'),
(2, '2024-01-12', 'Present', 'On time'),
(3, '2024-01-10', 'Present', 'On time'),
(3, '2024-01-11', 'Present', 'On time'),
(4, '2024-01-10', 'Present', 'On time'),
(4, '2024-01-11', 'Present', 'On time'),
(5, '2024-01-10', 'Late', 'Arrived 15 minutes late'),
(6, '2024-01-10', 'Absent', 'Personal reasons'),
(7, '2024-01-10', 'Present', 'On time');

-- ============================================
-- DATABASE VIEWS FOR REPORTING
-- ============================================

-- View: Student Performance Summary
CREATE OR REPLACE VIEW `student_performance_view` AS
SELECT 
    s.id,
    s.roll_number,
    CONCAT(s.first_name, ' ', s.last_name) AS full_name,
    s.class,
    COUNT(DISTINCT g.id) AS total_subjects,
    ROUND(AVG(g.percentage), 2) AS average_percentage,
    MAX(g.percentage) AS highest_score,
    MIN(g.percentage) AS lowest_score,
    s.status
FROM students s
LEFT JOIN grades g ON s.id = g.student_id
GROUP BY s.id, s.roll_number, s.first_name, s.last_name, s.class, s.status
ORDER BY average_percentage DESC;

-- View: Attendance Summary
CREATE OR REPLACE VIEW `attendance_summary_view` AS
SELECT 
    s.id,
    s.roll_number,
    CONCAT(s.first_name, ' ', s.last_name) AS full_name,
    s.class,
    COUNT(CASE WHEN a.status = 'Present' THEN 1 END) AS present_days,
    COUNT(CASE WHEN a.status = 'Absent' THEN 1 END) AS absent_days,
    COUNT(CASE WHEN a.status = 'Late' THEN 1 END) AS late_days,
    COUNT(a.id) AS total_days,
    ROUND((COUNT(CASE WHEN a.status = 'Present' THEN 1 END) / COUNT(a.id)) * 100, 2) AS attendance_percentage
FROM students s
LEFT JOIN attendance a ON s.id = a.student_id
GROUP BY s.id, s.roll_number, s.first_name, s.last_name, s.class
ORDER BY attendance_percentage DESC;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure: Get student's complete record
DELIMITER $$

CREATE PROCEDURE IF NOT EXISTS `get_student_record`(IN p_student_id INT)
BEGIN
    SELECT 
        s.*,
        COUNT(DISTINCT g.id) AS total_grades,
        ROUND(AVG(g.percentage), 2) AS average_grade,
        COUNT(DISTINCT a.id) AS total_attendance_records
    FROM students s
    LEFT JOIN grades g ON s.id = g.student_id
    LEFT JOIN attendance a ON s.id = a.student_id
    WHERE s.id = p_student_id
    GROUP BY s.id;
END$$

DELIMITER ;

-- ============================================
-- END OF DATABASE SETUP
-- ============================================
