<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');

require_once('app/models/CategoryModel.php');
require_once 'app/models/OrderModel.php';
class ProductController
{
    private $productModel;
    private $db;
    public function __construct()
    {
        SessionHelper::requireLogin();
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    public function index()
    {
        include 'app/views/product/list.php';
    }
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    public function add()
    {
        SessionHelper::requireAdmin();
        include_once 'app/views/product/add.php';
    }
    public function save()
    {
        // Moved to API
    }
    public function edit($id)
    {
        SessionHelper::requireAdmin();
        include 'app/views/product/edit.php';
    }
    public function update()
    {
        // Moved to API
    }
    public function delete($id)
    {
        // Moved to API
    }
    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType !=
            "jpeg" && $imageFileType != "gif"
        ) {

            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        // Lưu file

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }


    public function addToCart($id)
    {
        SessionHelper::requireLogin();
        $product = $this->productModel->getProductById($id);
        if (!$product) {

            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? '/PhanDuongQuocNhat/Product';
        header("Location: $referer");
    }
    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }
    public function checkout()
    {
        SessionHelper::requireLogin();
        include 'app/views/product/checkout.php';
    }
    public function processCheckout()
    {
        SessionHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name   = trim($_POST['name'] ?? '');
            $phone  = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            $this->db->beginTransaction();
            try {
                // Lưu đơn hàng
                $user_id = $_SESSION['user_id'] ?? null;
                $query = "INSERT INTO orders (name, phone, address, user_id) VALUES (:name, :phone, :address, :user_id)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();

                $order_id = $this->db->lastInsertId();  // ← Lấy ID vừa tạo

                // Lưu chi tiết sản phẩm
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                $this->db->commit();
                unset($_SESSION['cart']);

                // Redirect đến trang xác nhận kèm order_id
                header("Location: /PhanDuongQuocNhat/Product/orderConfirmation/$order_id");
                exit;
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation($order_id = null)
    {
        if (!$order_id || !is_numeric($order_id)) {
            echo "Không tìm thấy đơn hàng.";
            return;
        }

        require_once 'app/models/OrderModel.php'; 

        $orderModel = new OrderModel($this->db);
        $order = $orderModel->getOrderById($order_id);

        include 'app/views/product/orderConfirmation.php';
    }
    // Xóa một sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /PhanDuongQuocNhat/Product/cart');
        exit;
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateCartQuantity($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = (int)($_POST['quantity'] ?? 1);

            if ($quantity <= 0) {
                // Nếu số lượng <= 0 thì xóa sản phẩm
                if (isset($_SESSION['cart'][$id])) {
                    unset($_SESSION['cart'][$id]);
                }
            } else {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                }
            }

            header('Location: /PhanDuongQuocNhat/Product/cart');
            exit;
        }
    }

    // Lấy danh sách tất cả đơn hàng
    public function orderHistory()
    {
        SessionHelper::requireLogin();
        $orderModel = new OrderModel($this->db);
        if (SessionHelper::isAdmin()) {
            $orders = $orderModel->getAllOrders();
        } else {
            $orders = $orderModel->getAllOrders($_SESSION['user_id'] ?? null);
        }
        include 'app/views/product/orderHistory.php';
    }

    // Xem chi tiết một đơn hàng
    public function orderDetail($order_id)
    {
        SessionHelper::requireLogin();
        $orderModel = new OrderModel($this->db);
        
        if (SessionHelper::isAdmin()) {
            $order = $orderModel->getOrderById($order_id);
        } else {
            $order = $orderModel->getOrderById($order_id, $_SESSION['user_id'] ?? null);
        }

        if ($order) {
            $details = $orderModel->getOrderDetails($order_id);
            include 'app/views/product/orderDetail.php';
        } else {
            echo "Không tìm thấy đơn hàng hoặc bạn không có quyền xem.";
        }
    }
}
