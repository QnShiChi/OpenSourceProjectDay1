<?php
require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';

class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PhanDuongQuocNhat/account/register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirmpassword'] ?? '';

        $errors = [];

        if (empty($username)) $errors['username'] = "Vui lòng nhập tên đăng nhập!";
        if (empty($fullname)) $errors['fullname'] = "Vui lòng nhập họ và tên!";
        if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu!";
        if ($password !== $confirm) $errors['confirmPass'] = "Mật khẩu xác nhận không khớp!";

        if ($this->accountModel->getAccountByUsername($username)) {
            $errors['username'] = "Tài khoản này đã tồn tại!";
        }

        if (!empty($errors)) {
            include_once 'app/views/account/register.php';
            return;
        }

        if ($this->accountModel->save($username, $fullname, $password)) {
            header('Location: /PhanDuongQuocNhat/account/login');
            exit;
        } else {
            $errors['general'] = "Đăng ký thất bại!";
            include_once 'app/views/account/register.php';
        }
    }

    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    // ================== ĐĂNG NHẬP ==================
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PhanDuongQuocNhat/account/login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $account = $this->accountModel->getAccountByUsername($username);

        if ($account && password_verify($password, $account->password)) {
            session_start();
            $_SESSION['user_id']   = $account->id;
            $_SESSION['username']  = $account->username;
            $_SESSION['fullname']  = $account->fullname ?? $account->username;
            $_SESSION['role']      = $account->role;
            $_SESSION['avatar']    = $account->avatar ?? null;

            header('Location: /PhanDuongQuocNhat/Product');
            exit;
        }

        // Đăng nhập thất bại
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        include_once 'app/views/account/login.php';
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /PhanDuongQuocNhat/Product');
        exit;
    }

    public function profile()
    {
        SessionHelper::requireLogin();
        $account = $this->accountModel->getAccountById($_SESSION['user_id']);
        include_once 'app/views/account/profile.php';
    }

    public function updateProfile()
    {
        SessionHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $user_id = $_SESSION['user_id'];
            
            $avatar = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $target_dir = "uploads/avatars/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $imageFileType = strtolower(pathinfo($_FILES['avatar']["name"], PATHINFO_EXTENSION));
                $target_file = $target_dir . uniqid() . '.' . $imageFileType;
                if (move_uploaded_file($_FILES['avatar']["tmp_name"], $target_file)) {
                    $avatar = $target_file;
                }
            }
            
            if ($this->accountModel->updateProfile($user_id, $fullname, $avatar)) {
                $_SESSION['fullname'] = $fullname;
                if ($avatar) {
                    $_SESSION['avatar'] = $avatar;
                }
                header('Location: /PhanDuongQuocNhat/account/profile?success=profile');
                exit;
            } else {
                header('Location: /PhanDuongQuocNhat/account/profile?error=profile');
                exit;
            }
        }
    }

    public function changePassword()
    {
        SessionHelper::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            $account = $this->accountModel->getAccountById($user_id);
            
            if (!password_verify($current_password, $account->password)) {
                header('Location: /PhanDuongQuocNhat/account/profile?error=current_password');
                exit;
            }
            
            if ($new_password !== $confirm_password) {
                header('Location: /PhanDuongQuocNhat/account/profile?error=confirm_password');
                exit;
            }
            
            if ($this->accountModel->updatePassword($user_id, $new_password)) {
                header('Location: /PhanDuongQuocNhat/account/profile?success=password');
                exit;
            } else {
                header('Location: /PhanDuongQuocNhat/account/profile?error=password');
                exit;
            }
        }
    }
}