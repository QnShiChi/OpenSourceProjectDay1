<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chi tiết đơn hàng #<?= $order->id ?></h1>
        <a href="/PhanDuongQuocNhat/Product/orderHistory" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Quay lại lịch sử
        </a>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin người đặt</h5>
                </div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> <?= htmlspecialchars($order->name) ?></p>
                    <p><strong>SĐT:</strong> <?= htmlspecialchars($order->phone) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order->address) ?></p>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Sản phẩm trong đơn hàng</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($details)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($details as $detail): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <?php if ($detail->product_image): ?>
                                            <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($detail->product_image) ?>" 
                                                 alt="" style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px; border-radius: 4px;">
                                        <?php endif; ?>
                                        <div>
                                            <strong><?= htmlspecialchars($detail->product_name) ?></strong><br>
                                            <small>Giá: <?= number_format($detail->price, 0, ',', '.') ?> ₫ x <?= $detail->quantity ?></small>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-danger">
                                        <?= number_format($detail->price * $detail->quantity, 0, ',', '.') ?> ₫
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Tổng tiền:</span>
                            <span class="text-danger">
                                <?= number_format(array_sum(array_map(fn($d) => $d->price * $d->quantity, $details)), 0, ',', '.') ?> ₫
                            </span>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Không có sản phẩm trong đơn hàng này.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>