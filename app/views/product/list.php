<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
    <!-- Header Section -->
    <div class="bg-danger bg-gradient py-4 mb-4 shadow-sm">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="text-white mb-0 fw-semibold">
                    <i class="bi bi-shop"></i> Danh sách sản phẩm
                </h1>
                <a href="/PhanDuongQuocNhat/Product/add" class="btn btn-light btn-lg shadow-sm">
                    <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
                </a>
            </div>
        </div>
    </div>



    <!-- Carousel Banner Section -->
    <div class="container mb-5">
        <div id="bannerCarousel" class="carousel slide shadow-lg rounded-3 overflow-hidden" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <!-- Slides -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://mihanoi.vn/wp-content/uploads/2024/12/cac-mau-banner-tet-dep-va-an-tuong-cho-quang-cao-va-chuc-tet-13.jpg"
                        class="d-block w-100"
                        alt="Banner Tết 1"
                        style="height: 600px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-3">
                        <h5 class="fw-bold">Khuyến mãi Tết 2026</h5>
                        <p>Giảm giá đến 50% cho tất cả sản phẩm</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://actgroup.com.vn/wp-content/uploads/2025/12/99-mau-content-ban-hang-tet-2026-hap-dan-1.jpg"
                        class="d-block w-100"
                        alt="Banner Tết 2"
                        style="height: 600px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-3">
                        <h5 class="fw-bold">Mua sắm Tết vui vẻ</h5>
                        <p>Ưu đãi đặc biệt mùa lễ hội</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://marketplace.canva.com/EAGZKYhr2EY/2/0/1600w/canva-%C4%91%E1%BB%8F-v%C3%A0ng-gold-hi%E1%BB%87n-%C4%91%E1%BA%A1i-l%E1%BB%9Di-ch%C3%BAc-m%E1%BB%ABng-n%C4%83m-m%E1%BB%9Bi-t%E1%BA%BFt-nguy%C3%AAn-%C4%91%C3%A1n-vi%E1%BB%87t-nam-banner-Btpiq9VrtOg.jpg"
                        class="d-block w-100"
                        alt="Banner Tết 3"
                        style="height: 600px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-3">
                        <h5 class="fw-bold">Chúc mừng năm mới</h5>
                        <p>Năm mới nhiều sức khỏe và thành công</p>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <?php if (empty($products)): ?>
            <div class="bg-white rounded-3 shadow-sm p-5 text-center">
                <i class="bi bi-inbox display-1 text-secondary opacity-50"></i>
                <p class="text-muted fs-5 mt-3 mb-0">Chưa có sản phẩm nào. Hãy thêm sản phẩm đầu tiên của bạn!</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
                <?php foreach ($products as $product): ?>
                    <div class="col handle-redirect" data-product-id="<?php echo $product->getID(); ?>">
                        <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                            <!-- Product Image -->
                            <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 200px;">
                                <?php if ($product->getImage()): ?>
                                    <img src="<?php echo htmlspecialchars($product->getImage(), ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>"
                                        class="w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <i class="bi bi-image display-3 text-secondary opacity-25"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Product Info -->
                            <div class="card-body p-3">
                                <h5 class="card-title text-dark fw-semibold mb-2 lh-sm" style="height: 2.5rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                </h5>
                                <p class="card-text text-muted small mb-2" style="height: 2.5rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                                <div class="text-danger fw-bold fs-5">
                                    ₫<?php echo number_format($product->getPrice(), 0, ',', '.') ?>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="card-footer bg-light border-0 p-2">
                                <div class="d-flex gap-1">
                                    <a href="/PhanDuongQuocNhat/Product/edit/<?php echo $product->getID(); ?>"
                                        class="btn btn-warning btn-sm flex-fill fw-semibold">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <a href="/PhanDuongQuocNhat/Product/delete/<?php echo $product->getID(); ?>"
                                        class="btn btn-danger btn-sm fw-semibold"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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

    <script>
        document.querySelectorAll(".handle-redirect").forEach(card => {
            card.addEventListener("click", function(e) {
                const productId = this.dataset.productId;
                if (productId) {
                    window.location.href = `/PhanDuongQuocNhat/Product/detail/${productId}`
                }
            })
        })
    </script>
</body>

</html>