<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Cửa hàng - Phan Dương Quốc Nhật</title>
    
    <!-- Bootstrap 4.5.2 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome 5 (cho icon đẹp) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
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
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
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
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product/') !== false && !strpos($_SERVER['REQUEST_URI'], '/add')) ? 'active' : ''; ?>" 
                       href="/PhanDuongQuocNhat/Product/">
                        <i class="fas fa-box-open mr-1"></i> Sản phẩm
                    </a>
                </li>
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
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">