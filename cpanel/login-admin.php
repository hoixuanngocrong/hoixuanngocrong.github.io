<?php
require_once '../nduckien/session.php';
require_once '../nduckien/head.php';

if ($_login === true) {
    echo '<script>window.location.href = "home";</script>';
} else {

}

if ($_login == null) {
    if (isset($_POST['username'])) {

        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Check if input contains invalid characters
        if (!ctype_alnum($username)) {
            $_alert = '<div class="text-danger pb-2 font-weight-bold">Tên đăng nhập chỉ được chứa kí tự và số!</div>';
        } else {
            $select = _fetch(_select("*", 'account', "username='$username'"));

            if ($select != null && $select['password'] == $password) {
                // Kiểm tra xem tài khoản có nhân vật hay chưa dựa trên ID tài khoản
                $account_id = $select['id'];
                $result = _fetch(_select("*", 'player', "`account_id`='$account_id'"));

                if ($result != null) {
                    if (empty($select['ip_address'])) {
                        // Nếu cột ip_address chưa có dữ liệu, thực hiện cập nhật
                        $ip_address = $_SERVER['REMOTE_ADDR'];
                        _update('account', "ip_address='$ip_address'", "id='$account_id'");
                    }

                    // Check if the user is an admin (assuming 'admin' is the field name in the 'account' table)
                    if ($select['admin'] == 1) {
                        $_SESSION['account'] = $username;
                        $_SESSION['id'] = $select['id'];
                        // Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
                        echo '<script>window.location.href = "home";</script>';
                        exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng
                    } else {
                        $_alert = '<div class="text-danger pb-2 font-weight-bold">Tài khoản không phải là nhà điều hành!</div>';
                    }
                } else {
                    $_alert = '<div class="text-danger pb-2 font-weight-bold">Tài khoản này chưa tạo nhân vật!</div>';
                }
            } else {
                $_alert = '<div class="text-danger pb-2 font-weight-bold">Tên đăng nhập hoặc mật khẩu không hợp lệ, vui lòng kiểm tra lại!</div>';
            }
        }

    } elseif (isset($_POST['submit'])) {
        $_alert = '<div class="text-danger pb-2 font-weight-bold">Vui lòng nhập tên đăng nhập và mật khẩu!</div>';
    }
} else {
    // Chưa đăng nhập, chuyển hướng đến trang khác bằng JavaScript
    echo '<script>window.location.href = "home";</script>';
    exit(); // Đảm bảo dừng thực thi code sau khi chuyển hướng
}
?>
<div class="container" style="border-radius: 15px; background: #ffaf4c; padding: 0px">
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h4>ĐĂNG NHẬP - QUẢN TRỊ VIÊN</h4>
                <form id="form" method="POST">
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Tài khoản:</label>
                        <input class="form-control" type="text" name="username" id="username"
                            placeholder="Nhập tài khoản">
                    </div>
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Mật khẩu:</label>
                        <input class="form-control" type="password" name="password" id="password"
                            placeholder="Nhập mật khẩu">
                    </div>
                    <div class="form-check form-group">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="accept" id="accept" checked="">
                            Ghi nhớ đăng nhập
                        </label>
                        <a href="forgot-password" class="text-dark" style="float: right;">Quên mật
                            khẩu</a>
                    </div>
                    <?php
                    if (!empty($_alert)) {
                        echo $_alert;
                    }
                    ?>
                    <div id="notify" class="text-danger pb-2 font-weight-bold"></div>
                    <button class="btn btn-main form-control" type="sumbit">ĐĂNG
                        NHẬP</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    include_once '../nduckien/footer.php';
    ?>
</div>
</div>

</body>

</html>