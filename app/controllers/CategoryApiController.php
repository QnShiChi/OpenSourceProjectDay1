<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    private function authenticate($action = 'thêm, xoá hoặc sửa', $requireAdmin = true)
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!$authHeader && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $authHeader = $headers['Authorization'] ?? '';
        }

        if (preg_match('/[Bb]earer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            $jwtHandler = new JWTHandler();
            $decoded = $jwtHandler->decode($jwt);
            
            if ($decoded) {
                if (!$requireAdmin) {
                    return $decoded;
                }
                if (isset($decoded['role']) && $decoded['role'] === 'admin') {
                    return $decoded;
                } else {
                    http_response_code(403);
                    echo json_encode(['message' => 'Chỉ admin mới có quyền ' . $action]);
                    exit;
                }
            }
        }

        http_response_code(401);
        echo json_encode(['message' => 'Vui lòng đăng nhập']);
        exit;
    }

    // Lấy danh sách danh mục
    public function index()
    {
        header('Content-Type: application/json');
        $this->authenticate('xem', false);
        $categories = $this->categoryModel->getCategories();
        echo json_encode($categories);
    }

    // Lấy thông tin danh mục theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $this->authenticate('xem', false);
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Không tìm thấy danh mục']);
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . uniqid() . '.' . $imageFileType; 

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh quá lớn (tối đa 10MB).");
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception("Chỉ chấp nhận JPG, JPEG, PNG, GIF.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Lỗi khi upload hình ảnh.");
        }

        return $target_file;
    }

    // Thêm danh mục mới
    public function store()
    {
        header('Content-Type: application/json');
        $this->authenticate('thêm');
        $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
        
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $image = $data['image'] ?? '';
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadImage($_FILES['image']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => $e->getMessage()]]);
                return;
            }
        }
        
        $result = $this->categoryModel->addCategory($name, $description, $image);

        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Thêm danh mục thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Thêm danh mục thất bại']);
        }
    }

    // Cập nhật danh mục theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        $this->authenticate('sửa');
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            parse_str(file_get_contents("php://input"), $data);
        }
        
        if (empty($data) && !empty($_POST)) {
            $data = $_POST;
        }

        $existingCategory = $this->categoryModel->getCategoryById($id);
        if (!$existingCategory) {
            http_response_code(404);
            echo json_encode(['message' => 'Không tìm thấy danh mục']);
            return;
        }
        
        $name = array_key_exists('name', $data) ? $data['name'] : $existingCategory->name;
        $description = array_key_exists('description', $data) ? $data['description'] : $existingCategory->description;
        $image = array_key_exists('image', $data) ? $data['image'] : $existingCategory->image;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadImage($_FILES['image']);
                if ($existingCategory->image && file_exists($existingCategory->image)) {
                    unlink($existingCategory->image);
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => $e->getMessage()]]);
                return;
            }
        }
        
        $result = $this->categoryModel->updateCategory($id, $name, $description, $image);
        
        if ($result) {
            echo json_encode(['message' => 'Cập nhật danh mục thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Cập nhật danh mục thất bại']);
        }
    }

    // Xóa danh mục theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->authenticate('xoá');
        
        $category = $this->categoryModel->getCategoryById($id);
        if ($category && $category->image && file_exists($category->image)) {
            unlink($category->image);
        }

        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            echo json_encode(['message' => 'Xóa danh mục thành công']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Xóa danh mục thất bại']);
        }
    }
}
?>
