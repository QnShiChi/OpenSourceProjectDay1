<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <h1 class="mb-4">Lịch sử đơn hàng</h1>

    <?php if (!empty($orders)): ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Người đặt</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Số sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order->id ?></td>
                            <td><?= htmlspecialchars($order->name) ?></td>
                            <td><?= htmlspecialchars($order->phone) ?></td>
                            <td><?= htmlspecialchars($order->address) ?></td>
                            <td><?= $order->item_count ?></td>
                            <td class="fw-bold text-danger">
                                <?= number_format($order->total_amount, 0, ',', '.') ?> ₫
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                            <td>
                                <a href="/PhanDuongQuocNhat/Product/orderDetail/<?= $order->id ?>" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-history fa-3x mb-3 d-block"></i>
            <h4>Bạn chưa có đơn hàng nào</h4>
            <p>Hãy mua sắm và đặt hàng ngay hôm nay!</p>
            <a href="/PhanDuongQuocNhat/Product/" class="btn btn-primary mt-3">
                Xem sản phẩm
            </a>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="/PhanDuongQuocNhat/Product/" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>