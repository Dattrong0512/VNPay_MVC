<?php
require_once("./mvc/core/Controller.php");

class OrderHistory extends Controller
{
    public function __construct()
    {
        // Khởi tạo session nếu chưa có
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function Show()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            // Chưa đăng nhập, chuyển hướng đến trang đăng nhập kèm redirect
            $currentUrl = '/VNPay/OrderHistory/Show';
            header("Location: /VNPay/Auth/Show?redirect=" . urlencode($currentUrl));
            exit();
        }
        
        // Lấy customer_id từ session
        $customer_id = $_SESSION['user_id'];
        
        $model = $this->model("OrderHistoryModel");
        $orders = $model->GetAllOrders($customer_id);
        
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/OrderHistory",
            "Orders" => $orders,
            "Title" => "Lịch sử đơn hàng"
        ]);
    }
}