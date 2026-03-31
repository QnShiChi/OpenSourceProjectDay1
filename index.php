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

// Định tuyến API nếu URL bắt đầu bằng 'api'
if (isset($url[0]) && strtolower($url[0]) === 'api' && isset($url[1])) {
    $apiControllerName = ucfirst(strtolower($url[1])) . 'ApiController';
    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Support method overriding for PUT/DELETE via POST form-data
        if ($method === 'POST') {
            if (isset($_POST['_method'])) {
                $method = strtoupper($_POST['_method']);
            } elseif (isset($_GET['_method'])) {
                $method = strtoupper($_GET['_method']);
            } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $method = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            }
        }
        $id = $url[2] ?? null;
        $action = 'index';
        
        switch ($method) {
            case 'GET':
                $action = $id ? 'show' : 'index';
                break;
            case 'POST':
                $action = 'store';
                break;
            case 'PUT':
                if ($id) { $action = 'update'; }
                break;
            case 'DELETE':
                if ($id) { $action = 'destroy'; }
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }
        
        if (method_exists($controller, $action)) {
            if ($id) {
                call_user_func_array([$controller, $action], [$id]);
            } else {
                call_user_func_array([$controller, $action], []);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Action not found']);
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'API Controller not found']);
        exit;
    }
}

// Lấy controller mặc định
if (isset($url[0]) && strtolower($url[0]) === 'api-test') {
    $controllerName = 'ApiTestController';
} else {
    $controllerName = isset($url[0]) && $url[0] !== ''
        ? ucfirst(strtolower($url[0])) . 'Controller'
        : 'ProductController';
}

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
