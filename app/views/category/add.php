<?php include 'app/views/shares/header.php'; ?>

<h2>Thêm danh mục mới</h2>

<div id="error-messages" class="alert alert-danger" style="display:none;"></div>
<div id="success-messages" class="alert alert-success" style="display:none;"></div>

<form id="add-category-form">
    <div class="mb-3">
        <label>Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Mô tả</label>
        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
    </div>

    <div class="mb-3">
        <label>Hình ảnh danh mục</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Thêm danh mục</button>
    <a href="/PhanDuongQuocNhat/category/list" class="btn btn-secondary">Quay lại</a>
</form>

<script>
$(document).ready(function() {
    $('#add-category-form').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);

        $.ajax({
            url: '/PhanDuongQuocNhat/api/category',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#error-messages').hide();
                $('#success-messages').text(res.message || 'Thêm danh mục thành công!').show();
                setTimeout(() => {
                    window.location.href = '/PhanDuongQuocNhat/category/list';
                }, 1500);
            },
            error: function(err) {
                $('#success-messages').hide();
                let errMsg = err.responseJSON ? err.responseJSON.message : 'Thêm thất bại';
                $('#error-messages').text(errMsg).show();
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>