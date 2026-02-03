<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <h1 class="mb-4 text-center">Thêm sản phẩm mới</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/PhanDuongQuocNhat/Product/add" onsubmit="return validateForm();">
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Link ảnh sản phẩm (URL)</label>
                <input type="url" class="form-control" id="image" name="image"
                    placeholder="https://example.com/hinh-anh-san-pham.jpg">
                <div class="form-text">Dán link ảnh từ internet (jpg, png, ...)</div>
            </div>
            <button type="submit" class="btn btn-success w-100">Thêm sản phẩm</button>
        </form>

        <div class="text-center mt-4">
            <a href="/PhanDuongQuocNhat/Product/list" class="btn btn-link">← Quay lại danh sách</a>
        </div>
    </div>

    <script>
        function validateForm() {
            let name = document.getElementById('name').value.trim();
            let price = document.getElementById('price').value;
            let errors = [];
            if (name.length < 10 || name.length > 100) {
                errors.push('Tên sản phẩm phải từ 10 đến 100 ký tự.');
            }
            if (price <= 0 || isNaN(price)) {
                errors.push('Giá phải là số dương lớn hơn 0.');
            }
            if (errors.length > 0) {
                alert(errors.join('\n'));
                return false;
            }
            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>