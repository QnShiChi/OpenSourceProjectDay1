<?php include 'app/views/shares/header.php'; ?>

<h2>Sửa danh mục</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $err): ?>
            <p><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="/PhanDuongQuocNhat/category/update" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($category->id) ?>">
    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($category->image ?? '') ?>">

    <div class="mb-3">
        <label>Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category->name) ?>" required>
    </div>

    <div class="mb-3">
        <label>Mô tả</label>
        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($category->description) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Hình ảnh hiện tại:</label><br>
        <?php if ($category->image): ?>
            <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($category->image) ?>" alt="" width="120" style="margin-bottom:10px;">
        <?php else: ?>
            <p>Chưa có ảnh</p>
        <?php endif; ?>

        <label>Thay ảnh mới (nếu muốn):</label>
        <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="/PhanDuongQuocNhat/category/list" class="btn btn-secondary">Quay lại</a>
</form>

<?php include 'app/views/shares/footer.php'; ?>