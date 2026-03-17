<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 text-center">
    <div class="card shadow-lg border-0">
        <div class="card-body py-5">
            <i class="fas fa-check-circle text-success fa-5x mb-4"></i>
            <h1 class="mb-3">Đặt hàng thành công!</h1>
            <p class="lead mb-4">
                Cảm ơn bạn đã tin tưởng mua sắm tại cửa hàng của chúng tôi.<br>
                Đơn hàng của bạn đã được ghi nhận và đang được xử lý.
            </p>
            
            <?php if ($order): ?>
                <div class="alert alert-success mb-4">
                    <p class="mb-2 fw-bold">Mã đơn hàng: #<?= $order->id ?></p>
                </div>
                
                <a href="/PhanDuongQuocNhat/Product/orderDetail/<?= $order->id ?>" 
                   class="btn btn-primary btn-lg px-5 mb-3">
                    <i class="fas fa-eye me-2"></i> Xem chi tiết đơn hàng vừa đặt
                </a>
            <?php else: ?>
                <div class="alert alert-warning">
                    <p>Không tìm thấy thông tin đơn hàng mới nhất. Vui lòng kiểm tra lịch sử đơn hàng.</p>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="/PhanDuongQuocNhat/Product/" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>