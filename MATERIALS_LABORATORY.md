# Materials Management System - Laboratory Activity Documentation

## Overview
This laboratory activity implements a comprehensive file upload and download system for course materials with secure access controls and proper file management.

## 🗃️ Database Structure

### Materials Table Schema
```sql
CREATE TABLE materials (
    id INT(5) AUTO_INCREMENT PRIMARY KEY,
    course_id INT(5) UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at DATETIME NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE
);
```

## 🧪 Laboratory Implementation Steps

### ✅ Step 1: Database Migration
- **File**: `app/Database/Migrations/2025-11-29-091833_CreateMaterialsTable.php`
- **Command**: `php spark make:migration CreateMaterialsTable`
- **Migration**: `php spark migrate`
- **Table Created**: `materials` with proper foreign key constraints

### ✅ Step 2: Material Model
- **File**: `app/Models/MaterialModel.php`
- **Methods Implemented**:
  - `insertMaterial($data)` - Insert new material with timestamp
  - `getMaterialsByCourse($course_id)` - Get all materials for specific course
  - `getMaterialsForEnrolledCourses($user_id)` - Get materials for enrolled students
  - `userHasAccess($user_id, $material_id)` - Check enrollment-based access
  - `deleteMaterial($material_id)` - Remove material from database

### ✅ Step 3: Materials Controller
- **File**: `app/Controllers/Materials.php`
- **Methods Implemented**:
  - `upload($course_id)` - Display upload form and process file uploads
  - `delete($material_id)` - AJAX-based material deletion
  - `download($material_id)` - Secure file download with enrollment validation
  - `listByCourse($course_id)` - Admin view of course materials
  - `studentMaterials()` - Student view of accessible materials

### ✅ Step 4: File Upload Implementation
- **Upload Directory**: `public/uploads/materials/`
- **Validation Rules**:
  - File size limit: 10MB maximum
  - Allowed extensions: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG
  - Required file upload validation
- **File Naming**: Timestamp prefix to prevent conflicts
- **Database Storage**: Original filename and server path stored

### ✅ Step 5: Upload Form View
- **File**: `app/Views/admin/upload_material.php`
- **Features**:
  - Bootstrap 5.3.0 responsive design
  - File type validation and preview
  - Progress indication during upload
  - Comprehensive error handling and user feedback
  - File size and type information display

### ✅ Step 6: Student Materials Display
- **File**: `app/Views/student/materials.php`
- **Features**:
  - Materials grouped by enrolled courses
  - File type icons and visual indicators
  - Download buttons with progress tracking
  - Statistics dashboard (courses, materials, file types)
  - Responsive card-based layout

### ✅ Step 7: Secure Download Implementation
- **Security Features**:
  - Login requirement validation
  - Course enrollment verification for students
  - Admin bypass for full access
  - File existence checking before download
  - Force download with proper headers

### ✅ Step 8: Route Configuration
- **File**: `app/Config/Routes.php`
- **Routes Added**:
  ```php
  $routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
  $routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
  $routes->get('/admin/course/(:num)/materials', 'Materials::listByCourse/$1');
  $routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
  $routes->get('/materials/download/(:num)', 'Materials::download/$1');
  $routes->get('/student/materials', 'Materials::studentMaterials');
  ```

## 🔒 Security Features

### Access Control
1. **Authentication Required**: All material operations require valid user session
2. **Role-Based Access**: 
   - Admin: Upload, delete, and download all materials
   - Student: Download only materials from enrolled courses
3. **Enrollment Validation**: Students can only access materials from courses they're enrolled in

### File Security
1. **File Type Validation**: Whitelist of allowed extensions
2. **File Size Limits**: 10MB maximum to prevent abuse
3. **Unique Naming**: Timestamp prefixes prevent filename conflicts
4. **Directory Protection**: Upload directory outside web root access

### Input Validation
1. **CSRF Protection**: CodeIgniter's built-in CSRF tokens
2. **File Upload Validation**: Server-side validation rules
3. **SQL Injection Prevention**: Parameterized queries in models
4. **XSS Protection**: Output escaping in views

## 🧪 Testing Procedures

### Admin Testing (Upload Functionality)
1. **Login as Admin**:
   ```
   URL: /auth/login
   Credentials: admin@example.com / password
   ```

2. **Access Course Materials**:
   ```
   URL: /admin/course/{course_id}/materials
   Expected: List of existing materials or empty state
   ```

3. **Upload Material**:
   ```
   URL: /admin/course/{course_id}/upload
   Test Files: PDF, DOC, PPT, image files
   Expected: Successful upload with database record
   ```

### Student Testing (Download Functionality)
1. **Login as Student**:
   ```
   URL: /auth/login
   Credentials: student@example.com / password
   ```

2. **View Materials**:
   ```
   URL: /student/materials
   Expected: Materials from enrolled courses only
   ```

3. **Download Material**:
   ```
   Action: Click download button
   Expected: File downloaded successfully
   ```

### Security Testing
1. **Unauthorized Access Test**:
   ```
   Test: Access download link while not enrolled
   Expected: Access denied message
   ```

2. **File Type Validation Test**:
   ```
   Test: Upload prohibited file type (.exe, .php)
   Expected: Validation error
   ```

3. **File Size Validation Test**:
   ```
   Test: Upload file larger than 10MB
   Expected: Size limit error
   ```

## 📊 Testing Results Expected

### Database Verification
- Materials table created with proper foreign keys
- Records inserted with correct course_id relationships
- File paths stored accurately

### File System Verification
- Files stored in `public/uploads/materials/`
- Unique filenames with timestamp prefixes
- Original filenames preserved in database

### User Interface Verification
- Admin upload form displays correctly
- Student materials view shows enrolled course materials only
- Download functionality works for authorized users
- Error messages display appropriately

## 📋 Required Screenshots for Submission

1. **Database Schema**: 
   - phpMyAdmin view of materials table structure
   - Sample records with foreign key relationships

2. **Admin Upload Interface**:
   - Upload form with file selection
   - Success message after upload
   - Materials list view

3. **Student Download Interface**:
   - Materials grouped by course
   - Download buttons and file information
   - Statistics dashboard

4. **File System**:
   - Upload directory contents
   - File naming convention demonstration

5. **GitHub Repository**:
   - Latest commit with materials management implementation
   - Repository structure showing new files

## 🚀 Deployment Notes

### Server Requirements
- PHP 8.2+ with file upload enabled
- MySQL/MariaDB for foreign key support
- Write permissions on uploads directory
- Maximum upload size configured (10MB+)

### Configuration
- Upload directory automatically created if missing
- Foreign key constraints ensure data integrity
- Bootstrap 5.3.0 for responsive design
- Font Awesome icons for enhanced UI

## 🎯 Learning Outcomes

1. **File Upload Implementation**: Complete file handling with validation
2. **Security Best Practices**: Access control and input validation
3. **Database Relationships**: Foreign key implementation and queries
4. **MVC Architecture**: Proper separation of concerns
5. **User Experience**: Responsive design and error handling

This materials management system demonstrates a complete file upload/download implementation with proper security measures and user experience considerations suitable for educational environments.