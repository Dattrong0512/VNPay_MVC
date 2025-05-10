<?php
require_once("./mvc/core/Controller.php");
class Cart extends Controller
{
    public function __construct()
    {
        // Khởi tạo session nếu chưa có
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Kiểm tra trạng thái đăng nhập qua API
    public function CheckLogin()
    {
        header('Content-Type: application/json');
        
        // Kiểm tra xem người dùng đã đăng nhập chưa
        $isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        
        if ($isLoggedIn) {
            echo json_encode(['loggedIn' => true, 'userId' => $_SESSION['user_id']]);
        } else {
            echo json_encode(['loggedIn' => false]);
        }
        exit();
    }
    
    function Show()
    {
        // Kiểm tra đăng nhập khi vào trang giỏ hàng
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            // Lưu URL hiện tại để sau khi đăng nhập quay lại
            $redirectUrl = "/VNPay/Cart/Show";
            header("Location: /VNPay/Auth/Show?redirect=" . urlencode($redirectUrl));
            exit();
        }
        
        // Lấy customer_id từ session
        $customer_id = $_SESSION['user_id'];
        
        $model = $this->model("CartModel");
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/Cart",
            "Model" => $model->GetCart($customer_id) // Truyền customer_id từ session
        ]);
    }
    
    public function DeleteItem()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            header("Location: /VNPay/Auth/Show?redirect=/VNPay/Cart/Show");
            exit();
        }
        
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

    public function AddToCart()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            // Lưu URL để quay lại sau khi đăng nhập
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/VNPay/Product/Show';
            header("Location: /VNPay/Auth/Show?redirect=" . urlencode($referer));
            exit();
        }
        
        // Lấy customer_id từ session
        $customer_id = $_SESSION['user_id'];
        
        // Lấy thông tin từ request
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
        $product_amount = isset($_POST['product_amount']) ? $_POST['product_amount'] : 1;
        $current_page = isset($_POST['current_page']) ? (int)$_POST['current_page'] : 1;
        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        
        if ($product_id) {
            // Thêm sản phẩm vào giỏ hàng
            $cartModel = $this->model("CartModel");
            $cart = $cartModel->GetCart($customer_id);
            $orders_id = !empty($cart) ? $cart[0]['orders_id'] : null;
            $result = $cartModel->InsertItem($orders_id, $customer_id, $product_id, $product_amount);
        }
        
        // Xây dựng URL chuyển hướng
        $redirectParams = array();
        
        // Thêm tham số trang
        if ($current_page > 1) {
            $redirectParams[] = "page=" . $current_page;
        }
        
        // Thêm tham số tìm kiếm nếu có
        if (!empty($search)) {
            $redirectParams[] = "search=" . urlencode($search);
        }
        
        // Thêm tham số danh mục nếu có
        if (!empty($category)) {
            $redirectParams[] = "category=" . $category;
        }
        
        // Tạo URL chuyển hướng
        $redirectUrl = "/VNPay/Product/Show";
        if (!empty($redirectParams)) {
            $redirectUrl .= "?" . implode("&", $redirectParams);
        }
        
        // Chuyển hướng với tham số đầy đủ
        header("Location: " . $redirectUrl);
        exit();
    }

    public function AddToCartAjax() {
        // Kiểm tra xem yêu cầu có phải POST không
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng']);
                exit;
            }
            
            // Lấy customer_id từ session
            $customer_id = $_SESSION['user_id'];
            
            // Lấy dữ liệu từ request
            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
            $product_amount = isset($_POST['product_amount']) ? $_POST['product_amount'] : 1;
            
            if ($product_id) {
                // Gọi model để thêm vào giỏ hàng
                $cartModel = $this->model("CartModel");
                $cart = $cartModel->GetCart($customer_id);
                $orders_id = !empty($cart) ? $cart[0]['orders_id'] : null;
                $result = $cartModel->InsertItem($orders_id, $customer_id, $product_id, $product_amount);
                
                // Trả về kết quả dạng JSON
                header('Content-Type: application/json');
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm vào giỏ hàng']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
            }
            exit;
        }
        
        // Nếu không phải POST request, chuyển hướng về trang sản phẩm
        header("Location: /VNPay/Product/Show");
    }
    

    public function Confirm_VNPay()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $tongtien = $_POST['sotien'];
        $orders_id = $_POST['orders_id'];
        session_start();
        $_SESSION['pending_order_id'] = $orders_id;

        $vnp_TmnCode = "XHNCAN88"; //Website ID in VNPAY System
        $vnp_HashSecret = "PCVN2A7YEHPR7H0F7Z70K1AP8A9Z53TO"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8080/VNPay/Payment/Show";
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
