<?php
require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';
require_once 'app/utils/JWTHandler.php';

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
        $isApi = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false || isset($_GET['api']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isApi) {
                http_response_code(405);
                echo json_encode(['message' => 'Phương thức không được hỗ trợ']);
                exit;
            }
            header('Location: /PhanDuongQuocNhat/account/register');
            exit;
        }

        if ($isApi) {
            $data = json_decode(file_get_contents("php://input"), true);
            $username = trim($data['username'] ?? '');
            $fullname = trim($data['fullname'] ?? '');
            $password = $data['password'] ?? '';
            $confirm  = $data['confirmpassword'] ?? '';
        } else {
            $username = trim($_POST['username'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirmpassword'] ?? '';
        }

        $errors = [];

        if (empty($username)) $errors['username'] = "Vui lòng nhập tên đăng nhập!";
        if (empty($fullname)) $errors['fullname'] = "Vui lòng nhập họ và tên!";
        if (empty($password)) $errors['password'] = "Vui lòng nhập mật khẩu!";
        if ($password !== $confirm) $errors['confirmPass'] = "Mật khẩu xác nhận không khớp!";

        if ($this->accountModel->getAccountByUsername($username)) {
            $errors['username'] = "Tài khoản này đã tồn tại!";
        }

        if (!empty($errors)) {
            if ($isApi) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['errors' => $errors]);
                exit;
            }
            include_once 'app/views/account/register.php';
            return;
        }

        if ($this->accountModel->save($username, $fullname, $password)) {
            if ($isApi) {
                http_response_code(201);
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Đăng ký thành công!']);
                exit;
            }
            header('Location: /PhanDuongQuocNhat/account/login');
            exit;
        } else {
            if ($isApi) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Đăng ký thất bại!']);
                exit;
            }
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
        $isApi = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false || isset($_GET['api']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isApi) {
                http_response_code(405);
                echo json_encode(['message' => 'Phương thức không được hỗ trợ']);
                exit;
            }
            header('Location: /PhanDuongQuocNhat/account/login');
            exit;
        }

        if ($isApi) {
            $data = json_decode(file_get_contents("php://input"), true);
            $username = trim($data['username'] ?? '');
            $password = $data['password'] ?? '';
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
        }

        $account = $this->accountModel->getAccountByUsername($username);

        if ($account && password_verify($password, $account->password)) {
            if ($isApi) {
                $jwtHandler = new JWTHandler();
                $payload = [
                    'user_id' => $account->id,
                    'username' => $account->username,
                    'role' => $account->role
                ];
                $token = $jwtHandler->encode($payload);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'message' => 'Đăng nhập thành công',
                    'token' => $token,
                    'user' => $payload
                ]);
                exit;
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id']   = $account->id;
            $_SESSION['username']  = $account->username;
            $_SESSION['fullname']  = $account->fullname ?? $account->username;
            $_SESSION['role']      = $account->role;
            $_SESSION['avatar']    = $account->avatar ?? null;

            // Generate JWT for hybrid web/api usage
            $jwtHandler = new JWTHandler();
            $payload = [
                'user_id' => $account->id,
                'username' => $account->username,
                'role' => $account->role
            ];
            $_SESSION['jwt_token'] = $jwtHandler->encode($payload);

            header('Location: /PhanDuongQuocNhat/Product');
            exit;
        }

        // Đăng nhập thất bại
        if ($isApi) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Tên đăng nhập hoặc mật khẩu không đúng!']);
            exit;
        }

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