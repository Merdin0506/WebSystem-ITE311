<?php
/**
 * Materials System Test Script
 * Run this from command line to test the materials functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

// Connect to database
$config = new \Config\Database();
$db = \Config\Database::connect();

echo "=== Materials Management System Test ===\n\n";

// Test 1: Check if materials table exists
echo "1. Testing database structure...\n";
try {
    $query = $db->query("DESCRIBE materials");
    $columns = $query->getResultArray();
    echo "✅ Materials table found with columns:\n";
    foreach ($columns as $col) {
        echo "   - {$col['Field']} ({$col['Type']})\n";
    }
} catch (Exception $e) {
    echo "❌ Materials table not found: " . $e->getMessage() . "\n";
}

// Test 2: Check foreign key constraints
echo "\n2. Testing foreign key constraints...\n";
try {
    $query = $db->query("SELECT 
        CONSTRAINT_NAME, 
        COLUMN_NAME, 
        REFERENCED_TABLE_NAME, 
        REFERENCED_COLUMN_NAME 
    FROM information_schema.KEY_COLUMN_USAGE 
    WHERE TABLE_NAME = 'materials' 
    AND REFERENCED_TABLE_NAME IS NOT NULL");
    $constraints = $query->getResultArray();
    
    if (!empty($constraints)) {
        echo "✅ Foreign key constraints found:\n";
        foreach ($constraints as $constraint) {
            echo "   - {$constraint['COLUMN_NAME']} → {$constraint['REFERENCED_TABLE_NAME']}.{$constraint['REFERENCED_COLUMN_NAME']}\n";
        }
    } else {
        echo "❌ No foreign key constraints found\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking constraints: " . $e->getMessage() . "\n";
}

// Test 3: Check if uploads directory exists
echo "\n3. Testing file system setup...\n";
$uploadsDir = FCPATH . 'uploads/materials';
if (is_dir($uploadsDir)) {
    echo "✅ Upload directory exists: $uploadsDir\n";
    if (is_writable($uploadsDir)) {
        echo "✅ Upload directory is writable\n";
    } else {
        echo "❌ Upload directory is not writable\n";
    }
} else {
    echo "❌ Upload directory does not exist: $uploadsDir\n";
}

// Test 4: Check model availability
echo "\n4. Testing model classes...\n";
try {
    $materialModel = new \App\Models\MaterialModel();
    echo "✅ MaterialModel class loaded successfully\n";
    
    // Test model methods
    $methods = ['insertMaterial', 'getMaterialsByCourse', 'userHasAccess'];
    foreach ($methods as $method) {
        if (method_exists($materialModel, $method)) {
            echo "✅ Method $method exists\n";
        } else {
            echo "❌ Method $method missing\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error loading MaterialModel: " . $e->getMessage() . "\n";
}

// Test 5: Check controller
echo "\n5. Testing controller availability...\n";
try {
    if (class_exists('\App\Controllers\Materials')) {
        echo "✅ Materials controller exists\n";
        $controller = new \App\Controllers\Materials();
        
        $methods = ['upload', 'download', 'delete', 'studentMaterials'];
        foreach ($methods as $method) {
            if (method_exists($controller, $method)) {
                echo "✅ Method $method exists\n";
            } else {
                echo "❌ Method $method missing\n";
            }
        }
    } else {
        echo "❌ Materials controller not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking controller: " . $e->getMessage() . "\n";
}

// Test 6: Check routes
echo "\n6. Testing routes configuration...\n";
$routeFile = APPPATH . 'Config/Routes.php';
$routeContent = file_get_contents($routeFile);
$expectedRoutes = [
    '/admin/course/(:num)/upload',
    '/materials/download/(:num)',
    '/materials/delete/(:num)',
    '/student/materials'
];

foreach ($expectedRoutes as $route) {
    if (strpos($routeContent, $route) !== false) {
        echo "✅ Route $route configured\n";
    } else {
        echo "❌ Route $route missing\n";
    }
}

// Test 7: Check view files
echo "\n7. Testing view files...\n";
$viewFiles = [
    'app/Views/admin/upload_material.php',
    'app/Views/admin/course_materials.php',
    'app/Views/student/materials.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists(ROOTPATH . $viewFile)) {
        echo "✅ View file exists: $viewFile\n";
    } else {
        echo "❌ View file missing: $viewFile\n";
    }
}

echo "\n=== Test Complete ===\n";
echo "If all tests pass, your materials management system is ready!\n";
echo "Access points:\n";
echo "- Admin Upload: /admin/course/{id}/upload\n";
echo "- Admin Materials: /admin/course/{id}/materials\n";
echo "- Student Materials: /student/materials\n";
echo "- Download: /materials/download/{id}\n";
?>