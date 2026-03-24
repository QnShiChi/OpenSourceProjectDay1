<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4"><i class="fas fa-user-circle text-primary"></i> Hồ sơ cá nhân</h2>

            <?php if (isset($_GET['success'])): ?>
                <?php if ($_GET['success'] == 'profile'): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Cập nhật thông tin thành công!</div>
                <?php elseif ($_GET['success'] == 'password'): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Đổi mật khẩu thành công!</div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] == 'current_password'): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Mật khẩu hiện tại không đúng!</div>
                <?php elseif ($_GET['error'] == 'confirm_password'): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Mật khẩu xác nhận không khớp!</div>
                <?php else: ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra, vui lòng thử lại!</div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row">
                <!-- Cập nhật thông tin cá nhân -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Thông tin cơ bản</h5>
                        </div>
                        <div class="card-body">
                            <form action="/PhanDuongQuocNhat/account/updateProfile" method="POST" enctype="multipart/form-data">
                                <div class="text-center mb-4">
                                    <?php if (!empty($account->avatar)): ?>
                                        <img src="/PhanDuongQuocNhat/<?= htmlspecialchars($account->avatar) ?>" 
                                             class="rounded-circle img-thumbnail" 
                                             style="width: 150px; height: 150px; object-fit: cover;" 
                                             alt="Avatar">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                            <i class="fas fa-user fa-5x text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="form-group">
                                    <label class="fw-bold">Tên đăng nhập</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($account->username) ?>" readonly disabled>
                                    <small class="form-text text-muted">Tên đăng nhập không thể thay đổi.</small>
                                </div>

                                <div class="form-group">
                                    <label class="fw-bold">Họ và tên</label>
                                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($account->fullname) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="fw-bold">Ảnh đại diện mới</label>
                                    <input type="file" name="avatar" class="form-control-file" accept="image/*">
                                </div>

                                <button type="submit" class="btn btn-primary d-block w-100 mt-4">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Đổi mật khẩu</h5>
                        </div>
                        <div class="card-body">
                            <form action="/PhanDuongQuocNhat/account/changePassword" method="POST">
                                <div class="form-group">
                                    <label class="fw-bold">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label class="fw-bold">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control" required minlength="6">
                                </div>

                                <div class="form-group">
                                    <label class="fw-bold">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                                </div>

                                <button type="submit" class="btn btn-warning d-block w-100 mt-4">
                                    <i class="fas fa-key"></i> Đổi mật khẩu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
