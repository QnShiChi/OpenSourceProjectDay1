<?php

class AdminController
{
    public function index()
    {
        // Require SessionHelper and verify admin role if available
        require_once 'app/helpers/SessionHelper.php';
        
        // This is a minimal check, you can enforce it further
        if (!SessionHelper::isLoggedIn() || !SessionHelper::isAdmin()) {
            // Optional: redirect to login if not admin 
            // header('Location: /PhanDuongQuocNhat/account/login');
            // exit;
        }

        // Include the Single Page Application View
        include 'app/views/admin/dashboard.php';
    }
}
?>
