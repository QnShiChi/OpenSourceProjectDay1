<?php include 'app/views/shares/header.php'; ?>

<h2>Sửa danh mục</h2>

<div id="error-messages" class="alert alert-danger" style="display:none;"></div>
<div id="success-messages" class="alert alert-success" style="display:none;"></div>

<div id="loading" class="text-center py-5">
    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
    <p class="mt-3">Đang tải dữ liệu...</p>
</div>

<form id="edit-category-form" style="display:none;">
    <input type="hidden" id="category_id" name="id">
    <input type="hidden" id="existing_image" name="existing_image">

    <div class="mb-3">
        <label>Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Mô tả</label>
        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
    </div>

    <div class="mb-3">
        <label>Hình ảnh hiện tại:</label><br>
        <div id="image-preview">
            <!-- Render via JS -->
        </div>
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="/PhanDuongQuocNhat/category/list" class="btn btn-secondary">Quay lại</a>
</form>

<script>
$(document).ready(function() {
    let parts = window.location.pathname.split('/');
    let catId = parts.pop();
    if(isNaN(catId)) {
        catId = parts.pop();
    }

    // Load dữ liệu danh mục
    $.ajax({
        url: '/PhanDuongQuocNhat/api/category/' + catId,
        method: 'GET',
        success: function(category) {
            $('#loading').hide();
            $('#edit-category-form').show();
            
            $('#category_id').val(category.id);
            $('#name').val(category.name);
            $('#description').val(category.description);
            $('#existing_image').val(category.image);

            if (category.image) {
                $('#image-preview').html(`<img src="/PhanDuongQuocNhat/${category.image}" alt="" width="120" style="margin-bottom:10px;">`);
            } else {
                $('#image-preview').html(`<p>Chưa có ảnh</p>`);
            }
        },
        error: function() {
            $('#loading').hide();
            $('#error-messages').text('Không tìm thấy danh mục.').show();
        }
    });

    // Cập nhật danh mục
    $('#edit-category-form').on('submit', function(e) {
        e.preventDefault();
        
        let id = $('#category_id').val();
        let formData = new FormData(this);

        $.ajax({
            url: '/PhanDuongQuocNhat/api/category/' + id + '?_method=PUT',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-HTTP-Method-Override', 'PUT');
            },
            success: function(res) {
                $('#error-messages').hide();
                $('#success-messages').text(res.message || 'Cập nhật danh mục thành công!').show();
                setTimeout(() => {
                    window.location.href = '/PhanDuongQuocNhat/category/list';
                }, 1500);
            },
            error: function(err) {
                $('#success-messages').hide();
                let errMsg = err.responseJSON ? err.responseJSON.message : 'Cập nhật thất bại';
                $('#error-messages').text(errMsg).show();
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>