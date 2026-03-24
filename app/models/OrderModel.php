<?php
class OrderModel
{
    private $conn;
    private $table_orders = "orders";
    private $table_details = "order_details";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả đơn hàng (cho admin) hoặc theo user nếu sau này có login
    public function getAllOrders($user_id = null)
    {
        $query = "SELECT o.id, o.name, o.phone, o.address, o.created_at, 
                         COUNT(od.id) as item_count, SUM(od.price * od.quantity) as total_amount
                  FROM " . $this->table_orders . " o
                  LEFT JOIN " . $this->table_details . " od ON o.id = od.order_id";
        
        if ($user_id !== null) {
            $query .= " WHERE o.user_id = :user_id";
        }
        
        $query .= " GROUP BY o.id ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        if ($user_id !== null) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy chi tiết một đơn hàng
    public function getOrderById($order_id, $user_id = null)
    {
        $query = "SELECT * FROM " . $this->table_orders . " WHERE id = :id";
        if ($user_id !== null) {
            $query .= " AND user_id = :user_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $order_id);
        if ($user_id !== null) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Lấy chi tiết sản phẩm trong đơn hàng
    public function getOrderDetails($order_id)
    {
        $query = "SELECT od.*, p.name as product_name, p.image as product_image
                  FROM " . $this->table_details . " od
                  LEFT JOIN product p ON od.product_id = p.id
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}