<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Sidebar danh mục -->
        <div class="col-lg-3 col-md-4 mb-4">
            <h4 class="mb-3 text-uppercase font-weight-bold" style="font-size: 1.1rem; letter-spacing: 1px;"><i class="fas fa-filter"></i> Danh mục</h4>
            <div class="list-group shadow-sm" id="category-list">
                <a href="#" class="list-group-item list-group-item-action active bg-dark border-dark cat-filter" data-id="all">
                    Tất cả sản phẩm
                </a>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">Danh sách sản phẩm</h1>
            </div>

            <div id="product-container">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    <p class="mt-3">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const isAdmin = <?= SessionHelper::isAdmin() ? 'true' : 'false' ?>;
    const isLoggedIn = <?= SessionHelper::isLoggedIn() ? 'true' : 'false' ?>;
    let allProducts = [];

    // Tải danh mục qua API
    $.ajax({
        url: '/PhanDuongQuocNhat/api/category',
        method: 'GET',
        dataType: 'json',
        success: function(categories) {
            if (!Array.isArray(categories)) {
                console.error("Dữ liệu category không phải là mảng:", categories);
                return;
            }
            let catHtml = '';
            categories.forEach(cat => {
                catHtml += `<a href="#" class="list-group-item list-group-item-action cat-filter" data-id="${cat.id}">${cat.name}</a>`;
            });
            $('#category-list').append(catHtml);
        },
        error: function(xhr, status, error) {
            console.error("Lỗi API Category:", status, error, xhr.responseText);
            $('#category-list').append(`<div class="text-danger p-2 small">❌ Lỗi tải danh mục. Nhấn F12 xem Console.</div>`);
        }
    });

    // Tải sản phẩm qua API
    $.ajax({
        url: '/PhanDuongQuocNhat/api/product',
        method: 'GET',
        dataType: 'json',
        success: function(products) {
            if (!Array.isArray(products)) {
                console.error("Dữ liệu product không phải là mảng:", products);
                $('#product-container').html(`
                    <div class="alert alert-danger text-center py-5">
                        <h4>Lỗi: Dữ liệu trả về không hợp lệ!</h4>
                        <pre class="mt-3 text-left bg-dark text-light p-3 rounded" style="font-size: 13px; max-height: 200px; overflow-y: auto;">${JSON.stringify(products, null, 2)}</pre>
                    </div>
                `);
                return;
            }
            allProducts = products;
            
            // Xử lý filter trên URL (nếu có category_id)
            const urlParams = new URLSearchParams(window.location.search);
            const initialCat = urlParams.get('category_id');
            if (initialCat) {
                $('.cat-filter').removeClass('active bg-dark border-dark');
                setTimeout(() => {
                    $(`.cat-filter[data-id="${initialCat}"]`).addClass('active bg-dark border-dark');
                }, 100); 
                renderProducts(allProducts.filter(p => p.category_id == initialCat));
            } else {
                renderProducts(allProducts);
            }
        },
        error: function(xhr, status, error) {
            console.error("Lỗi API Product:", status, error, xhr.responseText);
            $('#product-container').html(`
                <div class="alert alert-danger text-center py-5">
                    <h4>Lỗi hệ thống khi tải sản phẩm (${status})</h4>
                    <pre class="mt-3 text-left bg-dark text-light p-3 rounded" style="font-size: 13px; max-height: 200px; overflow-y: auto;">${xhr.responseText}</pre>
                </div>
            `);
        }
    });

    // Xử lý click lọc danh mục
    $(document).on('click', '.cat-filter', function(e) {
        e.preventDefault();
        $('.cat-filter').removeClass('active bg-dark border-dark');
        $(this).addClass('active bg-dark border-dark');
        
        let catId = $(this).data('id');
        if (catId === 'all') {
            renderProducts(allProducts);
        } else {
            renderProducts(allProducts.filter(p => p.category_id == catId));
        }
    });

    function renderProducts(products) {
        if(products.length === 0) {
            $('#product-container').html(`
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3 d-block"></i>
                    <h4>Chưa có sản phẩm nào</h4>
                    <p>Hãy thêm sản phẩm đầu tiên ngay bây giờ!</p>
                    ${isAdmin ? '<a href="/PhanDuongQuocNhat/Product/add" class="btn btn-primary mt-3">Thêm sản phẩm</a>' : ''}
                </div>
            `);
            return;
        }

        let html = '<div class="products-wrapper">';
        products.forEach(product => {
            let imgHtml = product.image ? `<img src="/PhanDuongQuocNhat/${product.image}" alt="${product.name}">` : `<div style="width:100%; height:100%; background:#f0f0f0; display:grid; place-items:center; border-radius:inherit;"><i class="fas fa-image fa-3x text-muted"></i></div>`;
            
            let adminButtons = isAdmin ? `
                <a href="/PhanDuongQuocNhat/Product/edit/${product.id}" class="cart-button button btn-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                <a href="#" class="cart-button button btn-danger delete-btn" data-id="${product.id}" title="Xóa"><i class="fas fa-trash-alt"></i></a>
            ` : '';

            html += `
                <div class="product-card">
                    <div class="image-container">
                        ${imgHtml}
                        <div class="price">${Number(product.price).toLocaleString('vi-VN')} ₫</div>
                    </div>

                    <div class="content">
                        <div class="brand">${product.category_name || 'Không xác định'}</div>
                        <div class="product-name">
                            <a href="/PhanDuongQuocNhat/Product/show/${product.id}" class="text-decoration-none text-dark">
                                ${product.name}
                            </a>
                        </div>
                    </div>

                    <div class="button-container">
                        <a href="/PhanDuongQuocNhat/Product/addToCart/${product.id}" class="buy-button button add-to-cart-btn">Thêm vào giỏ</a>
                        ${adminButtons}
                    </div>
                </div>
            `;
        });
        html += '</div>';
        $('#product-container').html(html);
    }

    // Xử lý click Thêm vào giỏ
    $(document).on('click', '.add-to-cart-btn', function(e) {
        if (!isLoggedIn) {
            e.preventDefault();
            alert('Bạn phải đăng nhập để mua sắm!');
            window.location.href = '/PhanDuongQuocNhat/account/login';
        }
    });

    // Xử lý Xóa qua API
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            let id = $(this).data('id');
            $.ajax({
                url: '/PhanDuongQuocNhat/api/product/' + id,
                method: 'DELETE',
                success: function(res) {
                    alert(res.message);
                    allProducts = allProducts.filter(p => p.id != id);
                    $('.cat-filter.active').click(); // Re-render logic
                },
                error: function(err) {
                    alert('Lỗi: ' + (err.responseJSON ? err.responseJSON.message : 'Xóa thất bại'));
                }
            });
        }
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>