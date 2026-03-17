<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <h1 class="mb-4">Giỏ hàng của bạn</h1>

    <?php if (!empty($cart)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <?php if ($item['image']): ?>
                                    <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($item['image']) ?>" 
                                         alt="<?= htmlspecialchars($item['name']) ?>" 
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; border-radius: 8px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($item['name']) ?></strong>
                            </td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> ₫</td>
                            <td>
                                <form action="/PhanDuongQuocNhat/Product/updateCartQuantity/<?= $id ?>" method="POST" class="d-flex align-items-center">
                                    <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>" 
                                            class="btn btn-outline-secondary btn-sm">-</button>
                                    
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                                           min="1" class="form-control mx-2 text-center" style="width: 70px;" readonly>
                                    
                                    <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" 
                                            class="btn btn-outline-secondary btn-sm">+</button>
                                </form>
                            </td>
                            <td class="fw-bold text-danger">
                                <?= number_format($subtotal, 0, ',', '.') ?> ₫
                            </td>
                            <td>
                                <a href="/PhanDuongQuocNhat/Product/removeFromCart/<?= $id ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <td colspan="4" class="text-end fw-bold fs-5">Tổng tiền:</td>
                        <td colspan="2" class="fw-bold text-danger fs-4">
                            <?= number_format($total, 0, ',', '.') ?> ₫
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/PhanDuongQuocNhat/Product/" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm
            </a>
            <a href="/PhanDuongQuocNhat/Product/checkout" class="btn btn-success btn-lg">
                Thanh toán <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    <?php else: ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
            <h4>Giỏ hàng của bạn đang trống</h4>
            <p>Hãy thêm sản phẩm vào giỏ để tiếp tục mua sắm!</p>
            <a href="/PhanDuongQuocNhat/Product/" class="btn btn-primary mt-3">
                Xem sản phẩm
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>