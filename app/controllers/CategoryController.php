<?php
require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        include 'app/views/category/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $image = '';

            $errors = [];

            if (empty($name)) {
                $errors['name'] = 'Tên danh mục không được để trống';
            }

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                } catch (Exception $e) {
                    $errors['image'] = $e->getMessage();
                }
            }

            if (!empty($errors)) {
                include 'app/views/category/add.php';
                return;
            }

            $success = $this->categoryModel->addCategory($name, $description, $image);

            if ($success) {
                header('Location: /PhanDuongQuocNhat/category/list');
                exit;
            } else {
                $errors['general'] = 'Thêm danh mục thất bại. Vui lòng thử lại.';
                include 'app/views/category/add.php';
            }
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }
        include 'app/views/category/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $existing_image = $_POST['existing_image'] ?? '';

            $errors = [];

            if (empty($name)) {
                $errors['name'] = 'Tên danh mục không được để trống';
            }
            if (empty($id) || !is_numeric($id)) {
                $errors['general'] = 'ID danh mục không hợp lệ';
            }

            $image = $existing_image;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                    if ($existing_image && file_exists($existing_image)) {
                        unlink($existing_image);
                    }
                } catch (Exception $e) {
                    $errors['image'] = $e->getMessage();
                }
            }

            if (!empty($errors)) {
                $category = (object)[
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'image' => $existing_image
                ];
                include 'app/views/category/edit.php';
                return;
            }

            $success = $this->categoryModel->updateCategory($id, $name, $description, $image);

            if ($success) {
                header('Location: /PhanDuongQuocNhat/category/list');
                exit;
            } else {
                $errors['general'] = 'Cập nhật thất bại.';
                $category = (object)[
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'image' => $image
                ];
                include 'app/views/category/edit.php';
            }
        }
    }
    public function delete($id)
    {
        if (!$id || !is_numeric($id)) {
            echo "ID không hợp lệ.";
            return;
        }

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }

        $productCount = $this->categoryModel->countProductsByCategory($id);

        if ($productCount > 0) {
            echo "<script>
                alert('Không thể xóa danh mục này vì vẫn còn " . $productCount . " sản phẩm thuộc danh mục.');
                window.location.href = '/PhanDuongQuocNhat/category/list';
              </script>";
            exit;
        }

        $success = $this->categoryModel->deleteCategory($id);

        if ($success) {
            if ($category->image && file_exists($category->image)) {
                unlink($category->image);
            }
            echo "<script>
                alert('Xóa danh mục thành công!');
                window.location.href = '/PhanDuongQuocNhat/category/list';
              </script>";
        } else {
            echo "<script>
                alert('Xóa danh mục thất bại.');
                window.location.href = '/PhanDuongQuocNhat/category/list';
              </script>";
        }
        exit;
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
}
