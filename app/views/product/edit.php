<?php include 'app/views/shares/header.php'; ?>
<h1>Sửa sản phẩm</h1>

<div id="error-messages" class="alert alert-danger" style="display:none;"></div>
<div id="success-messages" class="alert alert-success" style="display:none;"></div>

<form id="edit-product-form" style="display:none;">
    <input type="hidden" id="product_id" name="id">
    
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
        <label>Hình ảnh hiện tại:</label><br>
        <div id="image-preview">
            <!-- Render via JS -->
        </div>
        <label class="mt-2">Thay ảnh mới (nếu muốn):</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>

<div id="loading" class="text-center py-5">
    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
    <p class="mt-3">Đang tải dữ liệu...</p>
</div>

<a href="/PhanDuongQuocNhat/Product" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<script>
$(document).ready(function() {
    // Lấy ID từ URL (vd: /Product/edit/5)
    let parts = window.location.pathname.split('/');
    let productId = parts.pop();
    if(isNaN(productId)) {
        productId = parts.pop(); // Thử thêm lần nữa nếu dính dấu slash
    }

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
            
            // Tải thông tin sản phẩm
            loadProductContent(productId);
        }
    });

    function loadProductContent(id) {
        $.ajax({
            url: '/PhanDuongQuocNhat/api/product/' + id,
            method: 'GET',
            success: function(product) {
                $('#loading').hide();
                $('#edit-product-form').show();
                
                $('#product_id').val(product.id);
                $('#name').val(product.name);
                $('#description').val(product.description);
                $('#price').val(product.price);
                setTimeout(() => {
                    $('#category_id').val(product.category_id);
                }, 100);

                if (product.image) {
                    $('#image-preview').html(`<img src="/PhanDuongQuocNhat/${product.image}" alt="Product" style="max-width: 100px;">`);
                } else {
                    $('#image-preview').html(`<p class="text-muted">Chưa có ảnh</p>`);
                }
            },
            error: function() {
                $('#loading').hide();
                $('#error-messages').text('Không tìm thấy sản phẩm.').show();
            }
        });
    }

    // Xử lý submit form
    $('#edit-product-form').on('submit', function(e) {
        e.preventDefault();
        
        let id = $('#product_id').val();
        let formData = new FormData(this);

        // PHP không hỗ trợ đọc file từ form-data cho method PUT. 
        // Nên dùng POST qua Web API và gửi kèm _method để giả lập PUT nếu cần, HOẶC API hiện tại có đọc $_POST khi gửi qua PUT nếu trống JSON -> nên tôi đang gửi qua đường POST rồi API server sẽ coi như cập nhật nếu map route phù hợp. Tuy nhiên router index.php hiện nhận PUT theo method thực sự. 
        // Do PHP không parse $_POST trong request HTTP PUT (chỉ POST), chúng ta cần gửi yêu cầu dưới dạng POST kèm the query parameters đối với dữ liệu json, HOẶC đổi endpoint method thành POST giả PUT.
        // Cách tốt nhất là POST đến cùng url với '?_method=PUT' nếu backend hỗ trợ, hoặc gửi trực tiếp thay vì POST.
        
        $.ajax({
            url: '/PhanDuongQuocNhat/api/product/' + id + '?_method=PUT',
            method: 'POST', // Chuyển thành POST để PHP parse được file bằng Form-Data, sau đó backend router xử lý là PUT nếu kiểm tra _method
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                // Thêm header gửi PUT
                xhr.setRequestHeader('X-HTTP-Method-Override', 'PUT');
            },
            success: function(res) {
                $('#error-messages').hide();
                $('#success-messages').text(res.message || 'Cập nhật sản phẩm thành công!').show();
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