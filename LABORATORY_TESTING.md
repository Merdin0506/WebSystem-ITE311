# Laboratory Activity - Course Enrollment System Testing

## Overview
This document demonstrates the implementation and testing of the course enrollment system as per laboratory requirements.

## Implementation Status

### ✅ Step 1: Database Migration
- **File**: `app/Database/Migrations/2025-10-10-133802_CreateEnrollmentsTable.php`
- **Status**: Complete with proper foreign keys
- **Structure**: 
  - `id` (primary key, auto-increment)
  - `user_id` (int, foreign key to users table)
  - `course_id` (int, foreign key to courses table)
  - `enrollment_date` (datetime)

### ✅ Step 2: Enrollment Model
- **File**: `app/Models/EnrollmentModel.php`
- **Methods Implemented**:
  - `enrollUser($data)` - Insert new enrollment record
  - `getUserEnrollments($user_id)` - Fetch user's enrolled courses
  - `isAlreadyEnrolled($user_id, $course_id)` - Prevent duplicates

### ✅ Step 3: Course Controller
- **File**: `app/Controllers/Course.php`
- **Features**:
  - Login verification
  - AJAX request handling
  - Duplicate enrollment checking
  - JSON responses
  - Notification creation on successful enrollment

### ✅ Step 4: Dashboard Views
- **My Courses**: `app/Views/student/my_courses.php`
- **Available Courses**: `app/Views/student/available_courses.php`
- **Features**: Bootstrap cards, responsive design, dynamic updates

### ✅ Step 5: AJAX Implementation
- **Technology**: jQuery
- **Features**: 
  - Real-time enrollment without page reload
  - Loading states and user feedback
  - Error handling
  - Dynamic UI updates

### ✅ Step 6: Route Configuration
- **Route**: `POST /course/enroll` → `Course::enroll`
- **Status**: Configured and working

## Testing Instructions

### Basic Functionality Test
1. Login as a student
2. Navigate to "Available Courses"
3. Click "Enroll" button on any course
4. Verify success message appears
5. Check "My Courses" to see enrolled course
6. Return to "Available Courses" to confirm course is no longer available

### Database Verification
Check enrollments table:
```sql
SELECT e.*, c.title, u.name 
FROM enrollments e 
JOIN courses c ON e.course_id = c.id 
JOIN users u ON e.user_id = u.id;
```

## Security Testing (Step 9)

### Test Cases Implemented:

1. **Authorization Bypass Test**
   - Endpoint validates login status
   - Returns 401 for unauthorized access

2. **SQL Injection Prevention**
   - Using CodeIgniter models with prepared statements
   - Input validation implemented

3. **CSRF Protection**
   - CSRF tokens included in AJAX requests
   - CodeIgniter CSRF protection ready for activation

4. **Data Tampering Prevention**
   - Server uses session user ID, not client-supplied data
   - Proper authentication checks

5. **Input Validation**
   - Course existence validation
   - Numeric ID validation
   - Error handling for invalid inputs

## Current Test Data

### Available Courses:
1. Web Development Fundamentals
2. Database Management Systems
3. Object-Oriented Programming
4. Mobile App Development
5. Data Structures and Algorithms
6. Software Engineering Principles

### Test User:
- ID: 1
- Role: student
- Email: student@example.com

## Files Modified/Created:
- ✅ Migration: CreateEnrollmentsTable.php
- ✅ Model: EnrollmentModel.php  
- ✅ Controller: Course.php (fixed database connection)
- ✅ Views: my_courses.php, available_courses.php
- ✅ Routes: course/enroll endpoint
- ✅ JavaScript: AJAX enrollment functionality

## Next Steps for Testing:
1. Access http://localhost:8080/student/dashboard
2. Test enrollment functionality
3. Verify database records
4. Test security scenarios
5. Take screenshots as required

## Notes:
- All functionality is implemented and ready for testing
- Database tables exist with proper relationships
- AJAX requests include proper error handling
- Notification system integrated with enrollment