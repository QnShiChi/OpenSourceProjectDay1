<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <!-- Header Section -->
    <div class="bg-danger bg-gradient py-4 mb-4 shadow-sm">
        <div class="container">
            <h1 class="text-white mb-0 fw-semibold">
                <i class="bi bi-box-seam"></i> Chi tiết sản phẩm
            </h1>
        </div>
    </div>

    <div class="container my-5" style="max-width: 900px;">
        <?php if (isset($product) && $product): ?>
            <div class="card shadow border-0 rounded-3 overflow-hidden">
                <div class="row g-0">
                    <!-- Product Image -->
                    <div class="col-md-5">
                        <div class="bg-light d-flex align-items-center justify-content-center p-4" style="min-height: 400px;">
                            <?php if ($product->getImage()): ?>
                                <img src="<?php echo htmlspecialchars($product->getImage(), ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>"
                                     class="img-fluid rounded-3 shadow-sm"
                                     style="max-height: 400px; object-fit: contain;">
                            <?php else: ?>
                                <i class="bi bi-image display-1 text-secondary opacity-25"></i>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-7">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                    <i class="bi bi-tag"></i> ID: <?php echo $product->getID(); ?>
                                </span>
                            </div>

                            <h2 class="card-title fw-bold mb-3">
                                <?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </h2>

                            <div class="bg-danger bg-opacity-10 rounded-3 p-3 mb-4">
                                <div class="text-danger fw-bold display-6">
                                    ₫<?php echo number_format($product->getPrice(), 0, ',', '.') ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted fw-semibold mb-2">
                                    <i class="bi bi-card-text"></i> Mô tả sản phẩm
                                </h6>
                                <p class="text-dark lh-lg">
                                    <?php echo nl2br(htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8')); ?>
                                </p>
                            </div>

                            <hr class="my-4">

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="/PhanDuongQuocNhat/Product/list" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại
                                </a>
                                <a href="/PhanDuongQuocNhat/Product/edit/<?php echo $product->getID(); ?>" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Chỉnh sửa
                                </a>
                                <a href="/PhanDuongQuocNhat/Product/delete/<?php echo $product->getID(); ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info Section -->
            <div class="row mt-4 g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check text-success display-4 mb-3"></i>
                            <h6 class="fw-semibold">Bảo hành chính hãng</h6>
                            <p class="text-muted small mb-0">Đảm bảo chất lượng</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-truck text-primary display-4 mb-3"></i>
                            <h6 class="fw-semibold">Miễn phí vận chuyển</h6>
                            <p class="text-muted small mb-0">Giao hàng toàn quốc</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-arrow-repeat text-warning display-4 mb-3"></i>
                            <h6 class="fw-semibold">Đổi trả dễ dàng</h6>
                            <p class="text-muted small mb-0">Trong vòng 7 ngày</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center py-5">
                <div class="bg-white rounded-3 shadow-sm p-5">
                    <i class="bi bi-exclamation-triangle text-warning display-1 mb-3"></i>
                    <h3 class="mb-3">Sản phẩm không tồn tại</h3>
                    <p class="text-muted mb-4">Sản phẩm bạn đang tìm kiếm không có trong hệ thống.</p>
                    <a href="/PhanDuongQuocNhat/Product/list" class="btn btn-danger btn-lg">
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-shop text-danger"></i> Cửa hàng của chúng tôi
                    </h5>
                    <p class="text-white-50 mb-3">
                        Chuyên cung cấp các sản phẩm chất lượng cao với giá cả hợp lý. Uy tín - Chất lượng - Tận tâm.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 40px; height: 40px;">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-bold mb-3">Liên kết</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Trang chủ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Giới thiệu
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Customer Support -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Hỗ trợ khách hàng</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Chính sách đổi trả
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Chính sách bảo hành
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Hướng dẫn thanh toán
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50 text-decoration-none">
                                <i class="bi bi-chevron-right"></i> Câu hỏi thường gặp
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Liên hệ</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            Quận Mười, Hồ Chí Minh, Việt Nam
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone-fill text-danger"></i>
                            <a href="tel:0123456789" class="text-white-50 text-decoration-none">0123 456 789</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope-fill text-danger"></i>
                            <a href="mailto:info@shop.vn" class="text-white-50 text-decoration-none">info@shop.vn</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-clock-fill text-danger"></i>
                            8:00 - 22:00 (Hằng ngày)
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="row mt-4 pt-4 border-top border-secondary">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6 class="fw-semibold mb-3">Phương thức thanh toán</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-credit-card"></i> Visa
                        </span>
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-credit-card-2-front"></i> MasterCard
                        </span>
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-paypal"></i> PayPal
                        </span>
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-cash"></i> COD
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3">Đơn vị vận chuyển</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-truck"></i> Giao hàng nhanh
                        </span>
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-box-seam"></i> Giao hàng tiết kiệm
                        </span>
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-airplane"></i> J&T Express
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="bg-black py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <p class="text-white-50 mb-0 small">
                            © 2026 <strong>Phan Dương Quốc Nhật</strong>. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="#" class="text-white-50 text-decoration-none small me-3">Điều khoản sử dụng</a>
                        <a href="#" class="text-white-50 text-decoration-none small">Chính sách bảo mật</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>