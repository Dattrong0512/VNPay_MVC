<?php
require_once("./mvc/core/Controller.php");

class OrderHistory extends Controller
{
    public function Show()
    {
        // Mặc định lấy customer_id = 3
        $customer_id = 3;
        
        $model = $this->model("OrderHistoryModel");
        $orders = $model->GetAllOrders($customer_id);
        
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/OrderHistory",
            "Orders" => $orders
        ]);
    }
}