<?php
// index.php - Router chính
session_start();   // ← Phải đặt ở đây, chỉ 1 lần duy nhất

require_once 'app/helpers/SessionHelper.php';
require_once 'app/config/database.php';

// Xử lý URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = isset($url[0]) && $url[0] !== ''
    ? ucfirst(strtolower($url[0])) . 'Controller'
    : 'ProductController';

$action = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';

// Kiểm tra controller tồn tại
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller không tồn tại: ' . $controllerName);
}

require_once 'app/controllers/' . $controllerName . '.php';

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die('Action không tồn tại: ' . $action);
}

// Gọi action
call_user_func_array([$controller, $action], array_slice($url, 2));
