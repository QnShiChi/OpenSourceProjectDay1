    </div> <!-- Đóng thẻ container chính (từ header) -->

    <!-- Footer -->
    <footer class="bg-dark text-white pt-4 pb-3" style="margin-top: 15rem;">
        <div class="container">
            <div class="row">
                <!-- Cột 1: Thông tin & Copyright -->
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold">
                        <i class="fas fa-store me-2"></i> 
                        Overflow Shop
                    </h5>
                    <p class="text-muted small">
                        Hệ thống quản lý sản phẩm & danh mục<br>
                        Được xây dựng bởi Phan Dương Quốc Nhật
                    </p>
                    <p class="small mb-0">
                        © <?= date('Y') ?> Phan Dương Quốc Nhật. All rights reserved.
                    </p>
                </div>

                <!-- Cột 2: Liên kết nhanh -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-3">Liên kết nhanh</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <a href="/PhanDuongQuocNhat/Product/" class="text-white text-decoration-none hover-link">
                                <i class="fas fa-box-open me-2"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/PhanDuongQuocNhat/category/list" class="text-white text-decoration-none hover-link">
                                <i class="fas fa-tags me-2"></i> Danh mục
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/PhanDuongQuocNhat/Product/add" class="text-white text-decoration-none hover-link">
                                <i class="fas fa-plus-circle me-2"></i> Thêm sản phẩm
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Liên hệ</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i> 
                            <a href="mailto:your.email@example.com" class="text-white text-decoration-none hover-link">
                                pdqnshichi2005@gmail.com
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i> 
                            <span>0837386074</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt me-2"></i> 
                            <span>TP. Hồ Chí Minh</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="bg-light opacity-25 my-4">
            <div class="text-center small">
                Powered by Phan Duong Quoc Nhat | <?= date('Y') ?>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>