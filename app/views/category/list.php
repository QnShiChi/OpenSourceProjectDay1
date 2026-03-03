<?php include 'app/views/shares/header.php'; ?>

<h2>Danh sách danh mục</h2>

<a href="/PhanDuongQuocNhat/category/add" class="btn btn-primary mb-3">Thêm danh mục mới</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= htmlspecialchars($cat->id) ?></td>
                    <td><?= htmlspecialchars($cat->name) ?></td>
                    <td><?= htmlspecialchars($cat->description) ?></td>
                    <td>
                        <?php if ($cat->image): ?>
                            <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($cat->image) ?>" alt="" width="80" height="80" style="object-fit:cover;">
                        <?php else: ?>
                            Không có ảnh
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/PhanDuongQuocNhat/category/edit/<?= $cat->id ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="/PhanDuongQuocNhat/category/delete/<?= $cat->id ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">Chưa có danh mục nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'app/views/shares/footer.php'; ?>