<?php include 'app/views/shares/header.php'; ?>
<h1>Thêm sản phẩm mới</h1>

<div id="error-messages" class="alert alert-danger" style="display:none;"></div>
<div id="success-messages" class="alert alert-success" style="display:none;"></div>

<form id="add-product-form">
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <!-- Categories will be loaded via API -->
            <option value="">Đang tải danh mục...</option>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
</form>
<a href="/PhanDuongQuocNhat/Product" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<script>
$(document).ready(function() {
    // Tải danh mục qua API
    $.ajax({
        url: '/PhanDuongQuocNhat/api/category',
        method: 'GET',
        success: function(categories) {
            let options = '';
            categories.forEach(cat => {
                options += `<option value="${cat.id}">${cat.name}</option>`;
            });
            $('#category_id').html(options);
        }
    });

    // Xử lý submit form
    $('#add-product-form').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);

        $.ajax({
            url: '/PhanDuongQuocNhat/api/product',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#error-messages').hide();
                $('#success-messages').text(res.message || 'Thêm sản phẩm thành công!').show();
                setTimeout(() => {
                    window.location.href = '/PhanDuongQuocNhat/Product';
                }, 1500);
            },
            error: function(err) {
                $('#success-messages').hide();
                let errMsg = 'Có lỗi xảy ra.';
                if (err.responseJSON && err.responseJSON.errors) {
                    errMsg = Object.values(err.responseJSON.errors).join('<br>');
                } else if (err.responseJSON && err.responseJSON.message) {
                    errMsg = err.responseJSON.message;
                }
                $('#error-messages').html(errMsg).show();
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>