<?php include 'app/views/shares/header.php'; ?>

<!-- SweetAlert2 cho thông báo đẹp mắt -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid mt-4 mb-5">
    <div class="row">
        <!-- Sidebar Menu Admin -->
        <div class="col-md-2 mb-4">
            <div class="list-group shadow-sm">
                <a href="#" class="list-group-item list-group-item-action active fw-bold bg-dark border-dark" id="nav-product">
                    <i class="fas fa-box-open mr-2"></i> Quản lý Sản phẩm
                </a>
                <a href="#" class="list-group-item list-group-item-action fw-bold" id="nav-category">
                    <i class="fas fa-tags mr-2"></i> Quản lý Danh mục
                </a>
            </div>
        </div>

        <!-- Main Content (SPA Area) -->
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-primary" id="page-title">Quản lý Sản phẩm</h5>
                    <button class="btn btn-success btn-sm font-weight-bold" id="btn-add-new">
                        <i class="fas fa-plus mr-1"></i> Thêm Mới
                    </button>
                </div>
                <div class="card-body">
                    <!-- Khu vực tự động bơm HTML (Bảng Grid) -->
                    <div id="data-container">
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                            <p class="mt-3">Đang tải dữ liệu...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa tự động (Dùng chung) -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="spa-form">
          <div class="modal-header bg-light">
            <h5 class="modal-title font-weight-bold" id="modal-title">Thêm / Sửa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal-body-content">
              <!-- Form input sẽ được inject tự động bằng jQuery -->
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary" id="btn-save"><i class="fas fa-save mr-1"></i> Lưu Dữ Liệu</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Biến toàn cục xác định đang ở tab nào: 'product' hoặc 'category'
    let currentMode = 'product'; 
    let categoriesList = []; // Lưu sẵn danh sách category để render thẻ <select> cho product

    // Khởi đầu trang: Tải danh sách category ngầm, sau đó tải danh sách product hiển thị
    loadCategoriesData(function() {
        loadProducts();
    });

    // ----------------------------------------
    // CÁC HÀM GỌI API & RENDER TABLE (GET)
    // ----------------------------------------

    // Hàm gọi lấy bộ dữ liệu Categories dùng chung
    function loadCategoriesData(callback = null) {
        $.ajax({
            url: '/PhanDuongQuocNhat/api/category',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if(Array.isArray(data)) {
                    categoriesList = data;
                }
                if(callback) callback();
            },
            error: function() {
                console.error("Lỗi lấy danh mục nền");
                if(callback) callback();
            }
        });
    }

    // Tải và vẽ giao diện bảng Product
    function loadProducts() {
        $('#page-title').text('Quản lý Sản phẩm');
        $('#data-container').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        
        $.ajax({
            url: '/PhanDuongQuocNhat/api/product',
            method: 'GET',
            dataType: 'json',
            success: function(products) {
                if(!Array.isArray(products)) {
                    $('#data-container').html('<div class="alert alert-danger">Lỗi định dạng dữ liệu API.</div>');
                    return;
                }

                let html = `
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th width="60">ID</th>
                                <th width="100">Hình ảnh</th>
                                <th>Tên Sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá bán</th>
                                <th width="120">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                if (products.length === 0) {
                    html += `<tr><td colspan="6" class="text-center py-4">Chưa có dữ liệu</td></tr>`;
                } else {
                    products.forEach(p => {
                        let img = p.image ? `<img src="/PhanDuongQuocNhat/${p.image}" style="width:50px; height:50px; object-fit:cover; border-radius:5px;">` : `<i class="fas fa-box fa-2x text-muted"></i>`;
                        let price = Number(p.price).toLocaleString('vi-VN') + ' đ';
                        html += `
                            <tr>
                                <td>${p.id}</td>
                                <td>${img}</td>
                                <td class="font-weight-bold">${p.name}</td>
                                <td><span class="badge badge-info">${p.category_name || 'Không xác định'}</span></td>
                                <td class="text-danger font-weight-bold">${price}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="${p.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${p.id}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                }
                html += `</tbody></table>`;
                $('#data-container').html(html);
            },
            error: function(err) {
                $('#data-container').html('<div class="alert alert-danger">Lỗi tải dữ liệu sản phẩm!</div>');
            }
        });
    }

    // Tải và vẽ giao diện bảng Category
    function loadCategoriesList() {
        $('#page-title').text('Quản lý Danh mục');
        $('#data-container').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        
        $.ajax({
            url: '/PhanDuongQuocNhat/api/category',
            method: 'GET',
            dataType: 'json',
            success: function(cats) {
                categoriesList = Array.isArray(cats) ? cats : []; // Cập nhật luôn cache

                let html = `
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th width="60">ID</th>
                                <th width="100">Hình ảnh</th>
                                <th>Tên Danh mục</th>
                                <th>Mô tả</th>
                                <th width="120">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                if (categoriesList.length === 0) {
                    html += `<tr><td colspan="5" class="text-center py-4">Chưa có dữ liệu</td></tr>`;
                } else {
                    categoriesList.forEach(c => {
                        let img = c.image ? `<img src="/PhanDuongQuocNhat/${c.image}" style="width:50px; height:50px; object-fit:cover; border-radius:5px;">` : `<i class="fas fa-folder fa-2x text-muted"></i>`;
                        html += `
                            <tr>
                                <td>${c.id}</td>
                                <td>${img}</td>
                                <td class="font-weight-bold">${c.name}</td>
                                <td>${c.description || ''}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="${c.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${c.id}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                }
                html += `</tbody></table>`;
                $('#data-container').html(html);
            },
            error: function(err) {
                $('#data-container').html('<div class="alert alert-danger">Lỗi tải dữ liệu danh mục!</div>');
            }
        });
    }

    // ----------------------------------------
    // ĐIỀU HƯỚNG SIDEBAR
    // ----------------------------------------
    $('#nav-product').click(function(e) {
        e.preventDefault();
        currentMode = 'product';
        $('.list-group-item').removeClass('active bg-dark border-dark');
        $(this).addClass('active bg-dark border-dark');
        loadProducts();
    });

    $('#nav-category').click(function(e) {
        e.preventDefault();
        currentMode = 'category';
        $('.list-group-item').removeClass('active bg-dark border-dark');
        $(this).addClass('active bg-dark border-dark');
        loadCategoriesList();
    });

    // ----------------------------------------
    // TẠO GIAO DIỆN FORM ĐỘNG (THÊM / SỬA)
    // ----------------------------------------
    function getFormTemplate(mode, isEdit = false) {
        if (mode === 'product') {
            let catOptions = '<option value="">-- Chọn danh mục --</option>';
            categoriesList.forEach(c => {
                catOptions += `<option value="${c.id}">${c.name}</option>`;
            });

            return `
                <input type="hidden" id="entry_id" name="id">
                <input type="hidden" id="_method" name="_method"> <!-- Trick giả PUT nếu sửa -->
                
                <div class="form-group mb-3">
                    <label>Tên Sản Phẩm:</label>
                    <input type="text" class="form-control" name="name" id="input_name" required>
                </div>
                <div class="form-group mb-3">
                    <label>Danh Mục:</label>
                    <select class="form-control" name="category_id" id="input_category_id" required>
                        ${catOptions}
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Giá (VNĐ):</label>
                    <input type="number" class="form-control" name="price" id="input_price" required>
                </div>
                <div class="form-group mb-3">
                    <label>Mô tả:</label>
                    <textarea class="form-control" name="description" id="input_description" rows="3"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Hình ảnh (Để trống nếu ko đổi):</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
            `;
        } else {
            return `
                <input type="hidden" id="entry_id" name="id">
                <input type="hidden" id="_method" name="_method">
                
                <div class="form-group mb-3">
                    <label>Tên Danh Mục:</label>
                    <input type="text" class="form-control" name="name" id="input_name" required>
                </div>
                <div class="form-group mb-3">
                    <label>Mô tả:</label>
                    <textarea class="form-control" name="description" id="input_description" rows="3"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>Hình ảnh (Để trống nếu ko đổi):</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
            `;
        }
    }

    // ----------------------------------------
    // CÁC SỰ KIỆN NÚT BẤM (THÊM/SỬA/XÓA)
    // ----------------------------------------

    // Mở Form Thêm Mới
    $('#btn-add-new').click(function() {
        $('#modal-title').text(currentMode === 'product' ? 'THÊM SẢN PHẨM MỚI' : 'THÊM DANH MỤC MỚI');
        $('#modal-body-content').html(getFormTemplate(currentMode, false));
        $('#_method').val(''); // Không cần gán PUT vì là Thêm mới (POST)
        $('#formModal').modal('show');
    });

    // Mở Form Sửa
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');
        let endpoint = currentMode === 'product' ? '/PhanDuongQuocNhat/api/product/' + id : '/PhanDuongQuocNhat/api/category/' + id;
        
        // Tạm đổi nút Save thành Đang tải
        Swal.fire({ title: 'Đang tải dữ liệu...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

        $.ajax({
            url: endpoint,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Swal.close();
                $('#modal-title').text(currentMode === 'product' ? 'CẬP NHẬT SẢN PHẨM' : 'CẬP NHẬT DANH MỤC');
                $('#modal-body-content').html(getFormTemplate(currentMode, true));
                
                // Binding dữ liệu cũ vào input
                $('#entry_id').val(data.id);
                $('#_method').val('PUT'); // Cực kỳ quan trọng để API router PHP hiểu đây là Update Form Data
                $('#input_name').val(data.name);
                $('#input_description').val(data.description);
                if(currentMode === 'product') {
                    $('#input_price').val(data.price);
                    $('#input_category_id').val(data.category_id);
                }
                
                $('#formModal').modal('show');
            },
            error: function() {
                Swal.fire('Lỗi', 'Không thể tải chi tiết đối tượng.', 'error');
            }
        });
    });

    // Xử lý Gửi Form (Lưu dữ liệu) - Dùng chung
    $('#spa-form').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let id = $('#entry_id').val();
        let isEdit = id ? true : false;
        
        let endpoint = currentMode === 'product' ? '/PhanDuongQuocNhat/api/product' : '/PhanDuongQuocNhat/api/category';
        if (isEdit) {
            endpoint += '/' + id + '?_method=PUT'; 
            // Dù ta gửi PUT via header override, truyền _method trên GET param vẫn giúp an toàn ở router PHP
        }
        
        let btn = $('#btn-save');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang lưu...');

        $.ajax({
            url: endpoint,
            method: 'POST', // Gửi qua đường POST chứa _method=PUT để FormData gửi file ko bị hỏng trên PHP
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                if (isEdit) xhr.setRequestHeader('X-HTTP-Method-Override', 'PUT');
            },
            success: function(res) {
                $('#formModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: res.message || 'Lưu dữ liệu hoàn tất.',
                    timer: 1500,
                    showConfirmButton: false
                });
                // Tải lại bảng ngay lập tức
                if (currentMode === 'product') {
                    loadProducts();
                } else {
                    loadCategoriesList();
                    loadCategoriesData(); // Refresh list ngầm cho thẻ Select của product
                }
            },
            error: function(err) {
                let msg = 'Có lỗi xảy ra.';
                if (err.responseJSON && err.responseJSON.errors) {
                    msg = Object.values(err.responseJSON.errors).join('<br>');
                } else if (err.responseJSON && err.responseJSON.message) {
                    msg = err.responseJSON.message;
                }
                Swal.fire('Lỗi Thao Tác', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Lưu Dữ Liệu');
            }
        });
    });

    // Xóa (Dùng chung)
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        let endpoint = currentMode === 'product' ? '/PhanDuongQuocNhat/api/product/' + id : '/PhanDuongQuocNhat/api/category/' + id;
        
        Swal.fire({
            title: 'Bạn có chắc muốn xóa?',
            text: "Dữ liệu này sẽ không thể khôi phục!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Có, Xóa nó!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: endpoint,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function() {
                        Swal.fire('Đã Xóa!', 'Bản ghi đã bị xóa tĩnh lặng.', 'success');
                        
                        // Cập nhật lại màn hình
                        if(currentMode === 'product') {
                            // Tải lại csdl nếu muốn thật, hoặc xoá dòng table
                            loadProducts(); 
                        } else {
                            loadCategoriesList();
                            loadCategoriesData(); // Refresh list Select
                        }
                    },
                    error: function(err) {
                        Swal.fire('Không thành công', err.responseJSON ? err.responseJSON.message : 'Lỗi xóa', 'error');
                    }
                });
            }
        });
    });

});
</script>

<?php include 'app/views/shares/footer.php'; ?>
