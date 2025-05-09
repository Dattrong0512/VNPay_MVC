<?php
require_once("./mvc/core/Controller.php");

class Payment extends Controller
{
    public function Show($params)
    {
        // Lấy các tham số từ $params
        $data = $_GET;
        
        if (isset($data['vnp_ResponseCode']) && $data['vnp_ResponseCode'] === '00') {
            // Lấy orders_id từ session
            session_start();
            $orders_id = isset($_SESSION['pending_order_id']) ? $_SESSION['pending_order_id'] : null;
            
            if ($orders_id) {
                $transaction_info = json_encode($data);
                $cartModel = $this->model("CartModel");
                $cartModel->UpdateOrderStatus($orders_id, 'Completed', $transaction_info);
                
                unset($_SESSION['pending_order_id']);
            }
            $this->view("Pages/Payment", $params);
        }
    }
}