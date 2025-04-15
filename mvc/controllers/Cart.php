<?php
require_once("./mvc/core/Controller.php");
class Cart extends Controller
{
    function Show()
    {
        $model = $this->model("CartModel");
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/Cart", // Đường dẫn đến view Product
            "Model" => $model->GetCart() // Dữ liệu sản phẩm
        ]);
    }
    public function DeleteItem()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["orderdetail_id"])) {
            $orderdetail_id = $_POST["orderdetail_id"];
            $model = $this->model("CartModel");
            $isDeleted = $model->DeleteItem($orderdetail_id);

            if ($isDeleted) {
                // Xóa thành công, chuyển hướng về trang giỏ hàng
                header("Location: /VNPay/Cart/Show");
            } else {
                // Xóa thất bại, hiển thị thông báo lỗi
                echo "<p style='color: red; text-align: center;'>Xóa sản phẩm thất bại.</p>";
            }
        }
    }

    public function Confirm_VNPay()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $tongtien = $_POST['sotien'];
        $vnp_TmnCode = "XHNCAN88"; //Website ID in VNPAY System
        $vnp_HashSecret = "PCVN2A7YEHPR7H0F7Z70K1AP8A9Z53TO"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8080/VNPay/Payment";
        $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        //Config input format
        //Expire
        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        //thanh toan bang vnpay
        $vnp_TxnRef = time() . ""; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán đơn hàng đặt tại web';
        $vnp_OrderType = 'billpayment';

        $vnp_Amount = $tongtien * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $vnp_ExpireDate = $expire;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        header('Location: ' . $vnp_Url);
        die();
    }
}
