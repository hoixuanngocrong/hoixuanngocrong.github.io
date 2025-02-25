<?php

require_once '../nduckien/head.php';
if ($_login === null) {
    echo '<script>window.location.href = "login-admin";</script>';
}

// chỉ cho phép tài khoản có admin = 1 truy cập
if ($_admin != 1) {
    echo '<script>window.location.href="../home"</script>';
    exit;
}

?>
<div class="container color-forum pt-1 pb-1">
    <div class="row">
        <div class="col"> <a href="home" style="color: white">Quay lại menu admin</a> </div>
    </div>
</div>
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <br>
            <br>
            <h4>Vi Phạm Điều Khoản</h4><br>
            <b class="text text-danger">Lưu Ý: </b><br>
            - Khóa - Mở khóa những tài khoản vi phạm!
            <br>
            - Chỉ dùng cho những tài khoản không bị khóa do vi phạm hoặc được ân xá
            <br>
            <br>
            <?php
            $_alert = ''; // Khởi tạo biến $_alert với giá trị rỗng
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $_username = $_POST['username'];
                $_tinhtrang = $_POST['tinhtrang'];

                // Truy vấn cơ sở dữ liệu để kiểm tra tài khoản
                $query = "SELECT * FROM user WHERE username = :username";
                $statement = $conn->prepare($query);
                $statement->bindParam(':username', $_username, PDO::PARAM_STR);
                $statement->execute();

                if ($statement->rowCount() == 0) {
                    // Thông báo lỗi nếu tài khoản không tồn tại
                    $_alert = 'Tên tài khoản không tồn tại!';
                } else {
                    $row = $statement->fetch();
                    $is_banned = $row['ban'];

                    if ($_tinhtrang == 'MoKhoa') {
                        // Mở khóa tài khoản
                        if ($is_banned === '1') {
                            // Nếu tài khoản đã bị khóa, tiến hành mở khóa
                            $query2 = "UPDATE account SET ban = '0' WHERE username = :username";
                            $statement2 = $conn->prepare($query2);
                            $statement2->bindParam(':username', $_username, PDO::PARAM_STR);
                            if ($statement2->execute()) {
                                // Thông báo thành công khi mở khóa tài khoản thành công
                                $_alert = 'Mở khóa tài khoản thành công!';
                            } else {
                                $_alert = 'Lỗi: Kết nối đến máy chủ';
                            }
                        } else {
                            // Nếu tài khoản không bị khóa, hiển thị thông báo
                            $_alert = 'Tài khoản không bị khóa!';
                        }
                    } elseif ($_tinhtrang == 'Khoa') {
                        // Khóa tài khoản
                        if ($is_banned === '0') {
                            // Nếu tài khoản chưa bị khóa, tiến hành khóa tài khoản
                            $query2 = "UPDATE user SET ban = '1' WHERE username = :username";
                            $statement2 = $conn->prepare($query2);
                            $statement2->bindParam(':username', $_username, PDO::PARAM_STR);
                            if ($statement2->execute()) {
                                // Thông báo thành công khi khóa tài khoản thành công
                                $_alert = 'Khóa tài khoản thành công!';
                            } else {
                                $_alert = 'Lỗi: Kết nối đến máy chủ';
                            }
                        } else {
                            // Nếu tài khoản đã bị khóa, hiển thị thông báo
                            $_alert = 'Tài khoản đã bị khóa!';
                        }
                    }
                }
                $conn = null;
            }
            ?>
            <!-- Hiển thị biến $_alert -->
            <?php echo $_alert; ?>
            <br>
            <br>
            <form method="POST">
                <div class="mb-3">
                    <label class="font-weight-bold">Tên Tài Khoản</label>
                    <input type="text" class="form-control" name="username" id="username"
                        placeholder="Nhập tên tài khoản" required autocomplete="off">

                    <label class="font-weight-bold">Tình Trạng</label>
                    <select class="form-control" name="tinhtrang" id="tinhtrang" required>
                        <option value="MoKhoa">Mở Khóa</option>
                        <option value="Khoa">Khóa</option>
                    </select>
                </div>
                <button class="btn btn-main form-control" type="submit">Kích Hoạt</button>
            </form>
        </div>
    </div>
</div>
<?php
include_once '../nduckien/footer.php';
?>

</body><!-- Bootstrap core JavaScript -->

</html>