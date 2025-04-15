<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Pages\Payment.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VNPAY RESPONSE</title>
    <link href="VNPAY/public/assets/bootstrap.min.css" rel="stylesheet" />
    <link href="VNPAY/public/assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="VNPAY/public/assets/jquery-1.11.3.min.js"></script>
</head>

<body>
    <?php
    // Kiểm tra và lấy giá trị vnp_SecureHash
    $vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : null;

    // Khóa bí mật (thay bằng khóa thực tế của bạn)
    $vnp_HashSecret = "PCVN2A7YEHPR7H0F7Z70K1AP8A9Z53TO";

    $inputData = array();
    foreach ($_GET as $key => $value) {
        if (substr($key, 0, 4) == "vnp_") {
            $inputData[$key] = $value;
        }
    }

    // Loại bỏ vnp_SecureHash khỏi dữ liệu
    unset($inputData['vnp_SecureHash']);
    ksort($inputData);

    $hashData = "";
    foreach ($inputData as $key => $value) {
        $hashData .= urlencode($key) . "=" . urlencode($value) . "&";
    }
    $hashData = rtrim($hashData, "&");

    // Tạo chữ ký
    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    ?>
    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted">VNPAY RESPONSE</h3>
        </div>
        <div class="table-responsive">
            <div class="form-group">
                <label>Mã đơn hàng:</label>
                <label><?php echo isset($_GET['vnp_TxnRef']) ? htmlspecialchars($_GET['vnp_TxnRef']) : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Số tiền:</label>
                <label><?php echo isset($_GET['vnp_Amount']) ? number_format($_GET['vnp_Amount'] / 100, 0, ',', '.') . " VND" : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Nội dung thanh toán:</label>
                <label><?php echo isset($_GET['vnp_OrderInfo']) ? htmlspecialchars($_GET['vnp_OrderInfo']) : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Mã phản hồi (vnp_ResponseCode):</label>
                <label><?php echo isset($_GET['vnp_ResponseCode']) ? htmlspecialchars($_GET['vnp_ResponseCode']) : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Mã GD Tại VNPAY:</label>
                <label><?php echo isset($_GET['vnp_TransactionNo']) ? htmlspecialchars($_GET['vnp_TransactionNo']) : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Mã Ngân hàng:</label>
                <label><?php echo isset($_GET['vnp_BankCode']) ? htmlspecialchars($_GET['vnp_BankCode']) : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Thời gian thanh toán:</label>
                <label><?php echo isset($_GET['vnp_PayDate']) ? htmlspecialchars($_GET['vnp_PayDate']) : "Không có dữ liệu"; ?></label>
            </div>
            <div class="form-group">
                <label>Kết quả:</label>
                <label>
                    <?php
                    if ($secureHash == $vnp_SecureHash) {
                        if (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] == '00') {
                            echo "<span style='color:blue'>Giao dịch thành công</span>";
                        } else {
                            echo "<span style='color:red'>Giao dịch không thành công</span>";
                        }
                    } else {
                        echo "<span style='color:red'>Chữ ký không hợp lệ</span>";
                    }
                    ?>
                </label>
            </div>
        </div>
        <footer class="footer">
            <p>&copy; VNPAY <?php echo date('Y'); ?></p>
        </footer>
    </div>
</body>

</html>