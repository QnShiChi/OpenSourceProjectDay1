<?php
/**
 * SessionHelper - Hỗ trợ quản lý session và kiểm tra đăng nhập
 */

class SessionHelper {

    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     */
    public static function isLoggedIn() {
        return isset($_SESSION['username']) && !empty($_SESSION['username']);
    }

    /**
     * Kiểm tra người dùng có phải là Admin không
     */
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Lấy thông tin người dùng hiện tại
     */
    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'user_id'   => $_SESSION['user_id'] ?? null,
                'username'  => $_SESSION['username'],
                'fullname'  => $_SESSION['fullname'] ?? $_SESSION['username'],
                'role'      => $_SESSION['role'] ?? 'user'
            ];
        }
        return null;
    }

    /**
     * Kiểm tra quyền truy cập (ví dụ: chỉ admin mới vào được)
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /PhanDuongQuocNhat/account/login');
            exit;
        }
    }

    public static function requireAdmin() {
        if (!self::isAdmin()) {
            header('Location: /PhanDuongQuocNhat/product');
            exit;
        }
    }
}