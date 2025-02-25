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
            <h4>Cộng Tiền - Máy Chủ 1</h4><br>
                <b class="text text-danger">Lưu Ý: </b><br>
                - Hãy thoát game trước khi cộng tránh lỗi không mong muốn!
                <br>
                - Chỉ dùng cho những tài khoản không bị khóa do vi phạm
                <br>
                <br>
                <?php
                $_alert = '';

                // Xử lý khi form được submit
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Lấy dữ liệu từ form
                    $username = $_POST["username"];
                    $vnd = intval($_POST["vnd"]);

                    // Kiểm tra xem có tài khoản nào khớp với tên đăng nhập không
                    $sql_check = "SELECT * FROM user WHERE username = :username";
                    $statement_check = $conn->prepare($sql_check);
                    $statement_check->bindParam(':username', $username, PDO::PARAM_STR);
                    $statement_check->execute();

                    if ($statement_check->rowCount() == 0) {
                        $_alert = '<div class="alert alert-danger">Lỗi: Tài khoản không tồn tại!</div>';
                    } else {
                        $row = $statement_check->fetch();
                        if ($row["ban"] == 1) {
                            $_alert = '<div class="alert alert-danger">Lỗi: Tài khoản đã bị vi phạm và không thể cộng tiền!</div>';
                        } else {
                            // Cập nhật tiền
                            $sql_update = "UPDATE user SET vnd = vnd + :vnd WHERE username = :username";
                            $statement_update = $conn->prepare($sql_update);
                            $statement_update->bindParam(':vnd', $vnd, PDO::PARAM_INT);
                            $statement_update->bindParam(':username', $username, PDO::PARAM_STR);

                            if ($statement_update->execute()) {
                                $_alert = '<div class="alert alert-success">Cộng tiền thành công!</div>';
                            } else {
                                $_alert = '<div class="alert alert-warning">Lỗi: Kết nối đến máy chủ</div>';
                            }
                        }
                    }
                }

                // Đóng kết nối
                $conn = null;
                ?>
                <!-- Hiển thị biến $_alert -->
                <div id="alertContainer">
                    <?php echo $_alert; ?>
                </div>
                <form method="POST">
                    <div class="mb-3">
                        <label>Tên Tài Khoản:</label>
                        <input type="username" class="form-control" name="username" id="username"
                            placeholder="Nhập tên tài khoản" required autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label>Số Tiền:</label>
                        <input type="vnd" class="form-control" name="vnd" id="vnd" placeholder="Nhập số tiền cần cộng"
                            required autocomplete="vnd">
                    </div>
                    <button class="btn btn-main form-control" type="submit">Thực Hiện</button>
                </form>
        </div>
    </div>
</div>
<?php
include_once '../nduckien/footer.php';
?>

</body><!-- Bootstrap core JavaScript -->

</html>