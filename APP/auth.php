<?php
// Authentication helper functions

/**
 * Require user to have one of the specified roles
 * @param array $allowedRoles Array of allowed roles
 * @throws Exception if user is not logged in or doesn't have required role
 */
function requireRole($allowedRoles) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in
    if (!isset($_SESSION[CURRENT_ACCOUNT])) {
        http_response_code(401);
        throw new Exception("Unauthorized: User not logged in");
    }
    
    // If allowedRoles is empty or null, any logged-in user is allowed
    if (empty($allowedRoles)) {
        return true;
    }
    
    // Ensure allowedRoles is an array
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    // Get user's role/jenis
    $userRole = $_SESSION[CURRENT_ACCOUNT]['jenis'] ?? null;
    
    // Check if user has one of the allowed roles
    if (!in_array($userRole, $allowedRoles)) {
        http_response_code(403);
        throw new Exception("Forbidden: User does not have required role");
    }
    
    return true;
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION[CURRENT_ACCOUNT]);
}

/**
 * Get current logged-in user
 * @return array|null
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION[CURRENT_ACCOUNT] ?? null;
}
?>
