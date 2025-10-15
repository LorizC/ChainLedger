<?php
// auth_guard.php
session_start();

/**
 * Redirects users to login page if not logged in.
 * Optional: pass allowed roles array to restrict access further.
 *
 * @param array|null $allowedRoles ['business owner', 'manager'] etc.
 */
function auth_guard(array $allowedRoles = null): void {
    if (!isset($_SESSION['user'])) {
        header("Location: /ChainLedger-System-/index.php");
        exit;
    }

    // If roles are specified, restrict access
    if ($allowedRoles !== null) {
        $userRole = strtolower(trim($_SESSION['user']['company_role'] ?? ''));
        $allowedRoles = array_map('strtolower', $allowedRoles);

        if (!in_array($userRole, $allowedRoles)) {
            header("Location: /ChainLedger-System-/pages.php");
            exit;
        }
    }
}
