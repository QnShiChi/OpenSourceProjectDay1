<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <h1 class="mb-4 text-center">Sửa sản phẩm</h1>

        <form method="POST" action="/PhanDuongQuocNhat/Product/edit/<?php echo $product->getID(); ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>
<?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?>
                </textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01"
                    value="<?php echo htmlspecialchars($product->getPrice(), ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Link ảnh sản phẩm (URL)</label>
                <input type="url" class="form-control" id="image" name="image"
                    value="<?php echo htmlspecialchars($product->getImage() ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="https://example.com/hinh-anh-san-pham.jpg">
                <div class="form-text">Dán link ảnh từ internet</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
        </form>

        <div class="text-center mt-4">
            <a href="/PhanDuongQuocNhat/Product/list" class="btn btn-link">← Quay lại danh sách</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>