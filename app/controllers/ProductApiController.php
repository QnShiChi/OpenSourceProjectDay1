<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');

class ProductApiController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Lấy danh sách sản phẩm
    public function index()
    {
        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }

    // Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
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

    // Thêm sản phẩm mới
    public function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
        
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadImage($_FILES['image']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => $e->getMessage()]]);
                return;
            }
        }
        
        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product creation failed']);
        }
    }

    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            parse_str(file_get_contents("php://input"), $data);
        }
        
        // Cập nhật FormData PHP không tự parse PUT form-data, nên fallback sửa $_POST:
        if (empty($data) && !empty($_POST)) {
            $data = $_POST;
        }

        $existingProduct = $this->productModel->getProductById($id);
        if (!$existingProduct) {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
            return;
        }
        
        $name = array_key_exists('name', $data) ? $data['name'] : $existingProduct->name;
        $description = array_key_exists('description', $data) ? $data['description'] : $existingProduct->description;
        $price = array_key_exists('price', $data) ? $data['price'] : $existingProduct->price;
        $category_id = array_key_exists('category_id', $data) ? $data['category_id'] : $existingProduct->category_id;
        
        $image = null; // null means do not update image column if no new file is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            try {
                $image = $this->uploadImage($_FILES['image']);
                if ($existingProduct->image && file_exists($existingProduct->image)) {
                    unlink($existingProduct->image); // Xóa ảnh cũ
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => $e->getMessage()]]);
                return;
            }
        }
        
        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
        
        if ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product update failed']);
        }
    }

    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        
        $product = $this->productModel->getProductById($id);
        if ($product && $product->image && file_exists($product->image)) {
            unlink($product->image);
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product deletion failed']);
        }
    }
}
?>
