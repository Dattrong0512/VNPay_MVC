<?php
require_once("./mvc/core/Controller.php");

class Payment extends Controller
{
    public function Show($params)
    {
        // Lấy các tham số từ $params

        // Xử lý logic (ví dụ: kiểm tra kết quả thanh toán từ VNPay)
        $this->view("Pages/Payment", $params
        );
    }
}