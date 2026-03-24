<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Sidebar danh mục -->
        <div class="col-lg-3 col-md-4 mb-4">
            <h4 class="mb-3 text-uppercase font-weight-bold" style="font-size: 1.1rem; letter-spacing: 1px;"><i class="fas fa-filter"></i> Danh mục</h4>
            <div class="list-group shadow-sm">
                <a href="/PhanDuongQuocNhat/Product" class="list-group-item list-group-item-action <?= !isset($_GET['category_id']) ? 'active bg-dark border-dark' : '' ?>">
                    Tất cả sản phẩm
                </a>
                <?php if (isset($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <a href="/PhanDuongQuocNhat/Product?category_id=<?= $category->id ?>" 
                           class="list-group-item list-group-item-action <?= (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) ? 'active bg-dark border-dark' : '' ?>">
                            <?= htmlspecialchars($category->name) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">Danh sách sản phẩm</h1>
            </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-3x mb-3 d-block"></i>
            <h4>Chưa có sản phẩm nào</h4>
            <p>Hãy thêm sản phẩm đầu tiên ngay bây giờ!</p>
            <?php if (SessionHelper::isAdmin()): ?>
            <a href="/PhanDuongQuocNhat/Product/add" class="btn btn-primary mt-3">
                Thêm sản phẩm
            </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="products-wrapper">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="image-container">
                        <?php if ($product->image): ?>
                            <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                        <?php else: ?>
                            <div style="width:100%; height:100%; background:#f0f0f0; display:grid; place-items:center; border-radius:inherit;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="price"><?= number_format($product->price, 0, ',', '.') ?> ₫</div>
                    </div>

                    <div class="content">
                        <div class="brand"><?= htmlspecialchars($product->category_name ?? 'OVERFLOW') ?></div>
                        <div class="product-name">
                            <a href="/PhanDuongQuocNhat/Product/show/<?= $product->id ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($product->name) ?>
                            </a>
                        </div>
                    </div>

                    <div class="button-container">
                        <a href="/PhanDuongQuocNhat/Product/addToCart/<?= $product->id ?>" class="buy-button button">Thêm vào giỏ</a>
                        
                        <?php if (SessionHelper::isAdmin()): ?>
                            <a href="/PhanDuongQuocNhat/Product/edit/<?= $product->id ?>" class="cart-button button btn-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/PhanDuongQuocNhat/Product/delete/<?= $product->id ?>" class="cart-button button btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>