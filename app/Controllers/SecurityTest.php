<?php

namespace App\Controllers;

/**
 * Security Testing Controller for Laboratory Activity
 * This controller provides endpoints to test various security scenarios
 */
class SecurityTest extends BaseController
{
    /**
     * Test unauthorized access to enrollment
     */
    public function testUnauthorized()
    {
        // Clear session to simulate logged out user
        session()->destroy();
        
        return $this->response->setJSON([
            'test' => 'unauthorized_access',
            'message' => 'Session cleared. Now try to enroll via /course/enroll',
            'expected' => 'Should return 401 Unauthorized'
        ]);
    }
    
    /**
     * Test SQL injection attempt
     */
    public function testSqlInjection()
    {
        $maliciousInput = "1 OR 1=1";
        
        return $this->response->setJSON([
            'test' => 'sql_injection',
            'malicious_input' => $maliciousInput,
            'message' => 'Try sending this as course_id in enrollment request',
            'expected' => 'Should be properly sanitized by CodeIgniter models'
        ]);
    }
    
    /**
     * Test CSRF protection
     */
    public function testCsrf()
    {
        return $this->response->setJSON([
            'test' => 'csrf_protection',
            'current_token' => csrf_token(),
            'current_hash' => csrf_hash(),
            'message' => 'CSRF tokens for testing',
            'instruction' => 'Try enrollment without valid CSRF token'
        ]);
    }
    
    /**
     * Test data tampering by attempting to enroll another user
     */
    public function testDataTampering()
    {
        $currentUserId = session()->get('userID');
        
        return $this->response->setJSON([
            'test' => 'data_tampering',
            'current_user_id' => $currentUserId,
            'message' => 'Current logged in user ID',
            'instruction' => 'Try to modify user_id in request - should be ignored',
            'expected' => 'Server should use session user_id, not client-supplied data'
        ]);
    }
    
    /**
     * Test input validation with invalid course IDs
     */
    public function testInputValidation()
    {
        $testCases = [
            'non_numeric' => 'abc123',
            'negative' => -1,
            'zero' => 0,
            'non_existent' => 99999,
            'sql_injection' => "1'; DROP TABLE courses; --"
        ];
        
        return $this->response->setJSON([
            'test' => 'input_validation',
            'test_cases' => $testCases,
            'message' => 'Invalid course ID test cases',
            'instruction' => 'Try these course_id values in enrollment requests',
            'expected' => 'All should be properly validated and rejected'
        ]);
    }
}