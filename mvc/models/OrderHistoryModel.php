<?php
class OrderHistoryModel extends DB
{
    public function GetAllOrders($customer_id = 3)
    {
        $query = "SELECT o.orders_id, o.customer_id, o.order_date, 
                         o.status, o.total_price 
                  FROM orders o 
                  WHERE o.customer_id = ? and o.status = 'Completed'
                  ORDER BY o.order_date DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        // Thêm chi tiết sản phẩm cho mỗi đơn hàng
        foreach($orders as &$order) {
            $order['products'] = $this->GetOrderProducts($order['orders_id']);
        }
        
        return $orders;
    }
    
    public function GetOrderProducts($order_id)
    {
        $query = "SELECT p.product_name, od.product_amount, p.product_price
                  FROM orderdetail od
                  JOIN product p ON od.product_id = p.product_id
                  WHERE od.orders_id = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
}