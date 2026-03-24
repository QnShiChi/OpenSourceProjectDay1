<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy thông tin tài khoản theo username
    public function getAccountByUsername($username)
    {
        $query = "SELECT id, username, fullname, password, role, avatar, created_at 
                  FROM " . $this->table_name . " 
                  WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Đăng ký tài khoản mới
    public function save($username, $fullname, $password, $role = 'user')
    {
        $query = "INSERT INTO " . $this->table_name . " (username, fullname, password, role) 
                  VALUES (:username, :fullname, :password, :role)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $username = htmlspecialchars(strip_tags(trim($username)));
        $fullname = htmlspecialchars(strip_tags(trim($fullname)));
        $role     = htmlspecialchars(strip_tags(trim($role)));

        // Hash password trước khi lưu (RẤT QUAN TRỌNG)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Gán dữ liệu
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // (Tùy chọn) Lấy thông tin tài khoản theo ID
    public function getAccountById($id)
    {
        $query = "SELECT id, username, fullname, password, role, avatar, created_at 
                  FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function updateProfile($id, $fullname, $avatar = null)
    {
        $query = "UPDATE " . $this->table_name . " SET fullname = :fullname";
        if ($avatar !== null) {
            $query .= ", avatar = :avatar";
        }
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullname', $fullname);
        if ($avatar !== null) {
            $stmt->bindParam(':avatar', $avatar);
        }
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function updatePassword($id, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
