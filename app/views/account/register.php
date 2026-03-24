<?php include 'app/views/shares/header.php'; ?>

<style>
body {
    background-color: #e3e3e3;
}
.singup {
  color: #000;
  text-transform: uppercase;
  letter-spacing: 2px;
  display: block;
  font-weight: bold;
  font-size: x-large;
  margin-top: 1.5em;
}

.card {
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  min-height: 350px !important;
  width: 320px !important;
  flex-direction: column !important;
  gap: 35px !important;
  background: #e3e3e3 !important;
  box-shadow: 16px 16px 32px #c8c8c8, -16px -16px 32px #fefefe !important;
  border-radius: 8px !important;
  border: none !important;
  margin: 0 auto;
}

.inputBox,
.inputBox1 {
  position: relative;
  width: 260px;
}

.inputBox input,
.inputBox1 input {
  width: 100%;
  padding: 10px;
  outline: none;
  border: none;
  color: #000;
  font-size: 1em;
  background: transparent;
  border-left: 2px solid #000;
  border-bottom: 2px solid #000;
  transition: 0.1s;
  border-bottom-left-radius: 8px;
}

.inputBox span,
.inputBox1 span {
  margin-top: 5px;
  position: absolute;
  left: 0;
  transform: translateY(-4px);
  margin-left: 10px;
  padding: 10px;
  pointer-events: none;
  font-size: 12px;
  color: #000;
  text-transform: uppercase;
  transition: 0.5s;
  letter-spacing: 3px;
  border-radius: 8px;
  white-space: nowrap;
}

.inputBox input:valid~span,
.inputBox input:focus~span,
.inputBox1 input:valid~span,
.inputBox1 input:focus~span {
  transform: translateX(113px) translateY(-15px);
  font-size: 0.8em;
  padding: 5px 10px;
  background: #000;
  letter-spacing: 0.2em;
  color: #fff;
  border: 2px;
}

.inputBox input:valid,
.inputBox input:focus,
.inputBox1 input:valid,
.inputBox1 input:focus {
  border: 2px solid #000;
  border-radius: 8px;
}

.enter {
  height: 45px;
  width: 110px;
  border-radius: 5px;
  border: 2px solid #000;
  cursor: pointer;
  background-color: transparent;
  transition: 0.5s;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: 2px;
  margin-bottom: 3em;
}

.enter:hover {
  background-color: rgb(0, 0, 0);
  color: white;
}
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh; margin-top: 30px; margin-bottom: 30px;">
    <div class="card">
        <a class="singup">Đăng ký</a>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div style="color: red; font-size: 13px; text-align: center; width: 80%; margin-top: -15px;">
                <?php foreach ($errors as $err): ?>
                    <p style="margin: 0;"><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/PhanDuongQuocNhat/account/save" method="POST" style="display: flex; flex-direction: column; align-items: center; gap: 35px; width: 100%;">
            
            <div class="inputBox1">
                <input type="text" name="username" required="required" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <span class="user">Tên đăng nhập</span>
            </div>

            <div class="inputBox">
                <input type="text" name="fullname" required="required" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                <span>Họ và tên</span>
            </div>

            <div class="inputBox">
                <input type="password" name="password" required="required">
                <span>Mật khẩu</span>
            </div>

            <div class="inputBox">
                <input type="password" name="confirmpassword" required="required">
                <span>Nhập lại MK</span>
            </div>

            <button type="submit" class="enter">Đăng ký</button>
            
            <div style="margin-top: -30px; margin-bottom: 20px; font-size: 14px;">
                Đã có tài khoản? <a href="/PhanDuongQuocNhat/account/login" style="color: black; font-weight: bold;">Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>