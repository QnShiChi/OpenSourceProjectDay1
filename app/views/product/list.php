<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Danh sách sản phẩm</h1>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-3x mb-3 d-block"></i>
            <h4>Chưa có sản phẩm nào</h4>
            <p>Hãy thêm sản phẩm đầu tiên ngay bây giờ!</p>
            <a href="/PhanDuongQuocNhat/Product/add" class="btn btn-primary mt-3">
                Thêm sản phẩm
            </a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <?php if ($product->image): ?>
                            <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($product->image) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($product->name) ?>"
                                 style="height: 220px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="/PhanDuongQuocNhat/Product/show/<?= $product->id ?>" 
                                   class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($product->name) ?>
                                </a>
                            </h5>

                            <p class="card-text text-muted flex-grow-1">
                                <?= nl2br(htmlspecialchars(substr($product->description, 0, 120))) ?>
                                <?= strlen($product->description) > 120 ? '...' : '' ?>
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-danger fw-bold mb-0">
                                        <?= number_format($product->price, 0, ',', '.') ?> ₫
                                    </span>
                                    <small class="badge bg-secondary">
                                        <?= htmlspecialchars($product->category_name ?? 'Chưa phân loại') ?>
                                    </small>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="/PhanDuongQuocNhat/Product/edit/<?= $product->id ?>" 
                                       class="btn btn-warning btn-sm flex-fill">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <a href="/PhanDuongQuocNhat/Product/delete/<?= $product->id ?>" 
                                       class="btn btn-danger btn-sm flex-fill"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>