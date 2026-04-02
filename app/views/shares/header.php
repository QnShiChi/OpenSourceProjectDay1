<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Cửa hàng - Overflow Shop</title>
    
    <!-- Bootstrap 4.5.2 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- jQuery (Full version with AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Product Card style -->
    <link rel="stylesheet" href="/PhanDuongQuocNhat/css/product-card.css">
    
    <style>
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.4rem;
        }
        .nav-link {
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .active {
            color: #ffffff !important;
            background-color: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        .cart-icon {
            position: relative;
            font-size: 1.3rem;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -12px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 0.7rem;
            min-width: 18px;
            text-align: center;
            line-height: 1;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            background-color: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Brand / Logo -->
        <a class="navbar-brand" href="/PhanDuongQuocNhat/">
            <i class="fas fa-store me-2"></i> Overflow Shop
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product/') !== false && !strpos($_SERVER['REQUEST_URI'], '/add') && !strpos($_SERVER['REQUEST_URI'], '/cart') && !strpos($_SERVER['REQUEST_URI'], '/checkout')) ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/Product/">
                        <i class="fas fa-box-open mr-1"></i> Sản phẩm
                    </a>
                </li>
                <?php if (SessionHelper::isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/Product/add') !== false ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/Product/add">
                        <i class="fas fa-plus-circle mr-1"></i> Thêm SP
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/category/list') !== false ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/category/list">
                        <i class="fas fa-tags mr-1"></i> Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/category/add') !== false ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/category/add">
                        <i class="fas fa-folder-plus mr-1"></i> Thêm danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/api-test') !== false ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/api-test" target="_blank">
                        <i class="fas fa-cogs mr-1"></i> API Test
                    </a>
                </li>
                <?php endif; ?>
                <?php if (SessionHelper::isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/orderHistory') !== false ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/Product/orderHistory">
                        <i class="fas fa-history mr-1"></i> Đơn hàng
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Giỏ hàng -->
            <ul class="navbar-nav ml-3">
                <li class="nav-item">
                    <a class="nav-link cart-icon" href="/PhanDuongQuocNhat/Product/cart">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        $cartCount = 0;
                        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                        if ($cartCount > 0):
                        ?>
                            <span class="cart-badge"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <!-- Phần Đăng nhập / Đăng xuất -->
            <ul class="navbar-nav ml-3">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Đã đăng nhập -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                           role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="user-avatar mr-2">
                                <?php if (!empty($_SESSION['avatar'])): ?>
                                    <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Avatar" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </span>
                            <?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/PhanDuongQuocNhat/account/profile">
                                <i class="fas fa-user-circle mr-2"></i> Hồ sơ cá nhân
                            </a>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a class="dropdown-item" href="/PhanDuongQuocNhat/admin">
                                    <i class="fas fa-cog mr-2"></i> Quản trị
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="/PhanDuongQuocNhat/account/logout">
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Chưa đăng nhập -->
                    <li class="nav-item">
                        <a class="nav-link" href="/PhanDuongQuocNhat/account/login">
                            <i class="fas fa-sign-in-alt mr-1"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/PhanDuongQuocNhat/account/register">
                            <i class="fas fa-user-plus mr-1"></i> Đăng ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php if(isset($_SESSION['jwt_token'])): ?>
<script>
    localStorage.setItem('jwt_token', '<?= $_SESSION['jwt_token'] ?>');
</script>
<?php endif; ?>

<?php if(!isset($_SESSION['user_id'])): ?>
<script>
    localStorage.removeItem('jwt_token');
</script>
<?php endif; ?>

<script>
    // Cấu hình ngầm định để jQuery luôn luôn kẹp JWT Token khi gọi API
    // Dùng thuộc tính headers để tránh bị ghi đè nếu các trang khác có xài tham số beforeSend
    const jwt_token = localStorage.getItem('jwt_token');
    if (jwt_token) {
        $.ajaxSetup({
            headers: {
                'Authorization': 'Bearer ' + jwt_token
            }
        });
    }
</script>

<div class="container mt-4 mb-5">
    <!-- Nội dung chính của các trang sẽ nằm ở đây -->