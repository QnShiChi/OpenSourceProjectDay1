<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Nút quay lại -->
        <div class="col-12 mb-4">
            <a href="/PhanDuongQuocNhat/Product/" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>

        <?php if ($product): ?>
            <!-- Ảnh sản phẩm lớn -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm">
                    <?php if ($product->image): ?>
                        <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($product->image) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= htmlspecialchars($product->name) ?>"
                             style="max-height: 500px; object-fit: contain; background: #f8f9fa;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" 
                             style="height: 500px;">
                            <i class="fas fa-image fa-10x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 p-lg-5">
                        <h1 class="card-title mb-3"><?= htmlspecialchars($product->name) ?></h1>

                        <div class="d-flex align-items-center mb-4">
                            <span class="h3 text-danger fw-bold mb-0 me-3">
                                <?= number_format($product->price, 0, ',', '.') ?> ₫
                            </span>
                            <small class="badge bg-primary fs-6">
                                <?= htmlspecialchars($product->category_name ?? 'Chưa phân loại') ?>
                            </small>
                        </div>

                        <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                        <p class="text-muted lead" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($product->description)) ?>
                        </p>

                        <?php if (!empty($product->description)): ?>
                            <hr class="my-4">
                        <?php endif; ?>

                        <!-- Nút hành động -->
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="/PhanDuongQuocNhat/Product/addToCart/<?= $product->id ?>" 
                               class="btn btn-success btn-lg flex-grow-1">
                                <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                            </a>
                            <a href="/PhanDuongQuocNhat/Product/edit/<?= $product->id ?>" 
                               class="btn btn-warning btn-lg">
                                <i class="fas fa-edit me-2"></i> Sửa sản phẩm
                            </a>
                        </div>

                        <!-- Thông tin bổ sung (có thể thêm sau) -->
                        <div class="mt-5 small text-muted">
                            <p><strong>Mã sản phẩm:</strong> #<?= $product->id ?></p>
                            <p><strong>Ngày thêm:</strong> <?= date('d/m/Y', strtotime($product->created_at ?? 'now')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 d-block"></i>
                    <h4>Không tìm thấy sản phẩm</h4>
                    <p>Sản phẩm có thể đã bị xóa hoặc không tồn tại.</p>
                    <a href="/PhanDuongQuocNhat/Product/" class="btn btn-primary mt-3">
                        Quay về danh sách
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>