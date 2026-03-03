<?php include 'app/views/shares/header.php'; ?>

<h2>Thêm danh mục mới</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $err): ?>
            <p><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="/PhanDuongQuocNhat/category/save" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Mô tả</label>
        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($description ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Hình ảnh danh mục</label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Thêm danh mục</button>
    <a href="/PhanDuongQuocNhat/category/list" class="btn btn-secondary">Quay lại</a>
</form>

<?php include 'app/views/shares/footer.php'; ?>