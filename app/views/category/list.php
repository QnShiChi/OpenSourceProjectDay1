<?php include 'app/views/shares/header.php'; ?>

<h2>Danh sách danh mục</h2>

<div id="messages" class="alert mt-3" style="display:none;"></div>

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
    <tbody id="category-list-body">
        <tr><td colspan="5" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Đang tải dữ liệu...</td></tr>
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Tải danh sách category
    function loadCategories() {
        $.ajax({
            url: '/PhanDuongQuocNhat/api/category',
            method: 'GET',
            success: function(categories) {
                if (categories.length === 0) {
                    $('#category-list-body').html('<tr><td colspan="5" class="text-center">Chưa có danh mục nào.</td></tr>');
                    return;
                }

                let html = '';
                categories.forEach(cat => {
                    let imgHtml = cat.image 
                        ? `<img src="/PhanDuongQuocNhat/${cat.image}" alt="" width="80" height="80" style="object-fit:cover;">` 
                        : 'Không có ảnh';

                    html += `
                        <tr>
                            <td>${cat.id}</td>
                            <td>${cat.name}</td>
                            <td>${cat.description || ''}</td>
                            <td>${imgHtml}</td>
                            <td>
                                <a href="/PhanDuongQuocNhat/category/edit/${cat.id}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="${cat.id}">Xóa</a>
                            </td>
                        </tr>
                    `;
                });
                $('#category-list-body').html(html);
            },
            error: function() {
                $('#category-list-body').html('<tr><td colspan="5" class="text-center text-danger">Lỗi tải dữ liệu.</td></tr>');
            }
        });
    }

    loadCategories();

    // Xóa danh mục qua API
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if(confirm('Bạn chắc chắn muốn xóa danh mục này?')) {
            let id = $(this).data('id');
            $.ajax({
                url: '/PhanDuongQuocNhat/api/category/' + id,
                method: 'DELETE',
                success: function(res) {
                    $('#messages').removeClass('alert-danger').addClass('alert-success')
                                 .text(res.message || 'Xóa danh mục thành công!').show();
                    loadCategories();
                },
                error: function(err) {
                    let msg = err.responseJSON ? err.responseJSON.message : 'Xóa thất bại';
                    $('#messages').removeClass('alert-success').addClass('alert-danger')
                                 .text(msg).show();
                }
            });
        }
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>