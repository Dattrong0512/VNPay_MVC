<?php

class CartModel extends DB
{
    public function GetCart()
    {
        $query = "select  od.total_price, odd.orderdetail_id, product_name, product_price, odd.product_amount, od.orders_id, pd.product_id
                from
                orders od
                join orderdetail odd
                on od.orders_id = odd.orders_id
                join product pd
                on odd.product_id=pd.product_id
                where od.customer_id = 3 and od.status = 'Incompleted';
                ";
        $results = mysqli_query($this->conn, $query);
        return $results->fetch_all(MYSQLI_ASSOC);
    }


    public function DeleteItem($orderdetail_id): bool
    {
        $query = "DELETE FROM orderdetail WHERE orderdetail_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orderdetail_id); // "i" là kiểu integer
        $stmt->execute();
        return $stmt->affected_rows > 0; // Trả về true nếu xóa thành công
    }

    public function InsertItem($orders_id, $customer_id, $product_id, $product_amount)
    {
        $checkOrder = "SELECT orders_id FROM orders WHERE customer_id = ? and status = 'Incompleted'";
        $stmt = $this->conn->prepare($checkOrder);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $createOrder = "INSERT INTO orders (customer_id, status, total_price) VALUES ( ?, 'Incompleted', 0)";
            $stmt = $this->conn->prepare($createOrder);
            $stmt->bind_param("i",$customer_id);
            $stmt->execute();
            
            if ($stmt->affected_rows <= 0) {
                return false; 
            }
            $orders_id = $stmt->insert_id;
        } else {
            // Nếu đã có đơn hàng, cần lấy orders_id từ kết quả query
            $row = $result->fetch_assoc(); 
            $orders_id = $row['orders_id'];  // Dòng này đang thiếu!
        }
        
        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $checkItem = "SELECT orderdetail_id, product_amount FROM orderdetail 
                      WHERE orders_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($checkItem);
        $stmt->bind_param("ii", $orders_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $item = $result->fetch_assoc();
            $newAmount = $item["product_amount"] + $product_amount;
            
            $updateQuery = "UPDATE orderdetail SET product_amount = ? WHERE orderdetail_id = ?";
            $stmt = $this->conn->prepare($updateQuery);
            $stmt->bind_param("ii", $newAmount, $item["orderdetail_id"]);
            $stmt->execute();
            
            $success = $stmt->affected_rows > 0;
        } else {
            // Nếu sản phẩm chưa tồn tại, thêm mới
            $insertQuery = "INSERT INTO orderdetail (orders_id, product_id, product_amount) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($insertQuery);
            $stmt->bind_param("iii", $orders_id, $product_id, $product_amount);
            $stmt->execute();
            
            $success = $stmt->affected_rows > 0;
        }
        
        // Cập nhật tổng tiền cho đơn hàng
        if ($success) {
            $this->updateOrderTotal($orders_id);
        }
        
        return $success;
    }
    
    private function updateOrderTotal($orders_id)
    {
        // Tính tổng tiền từ tất cả các sản phẩm trong đơn hàng
        $query = "UPDATE orders o
                  SET total_price = (
                      SELECT SUM(p.product_price * od.product_amount)
                      FROM orderdetail od
                      JOIN product p ON od.product_id = p.product_id
                      WHERE od.orders_id = ?
                  )
                  WHERE o.orders_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $orders_id, $orders_id);
        $stmt->execute();
        
        return $stmt->affected_rows > 0;
    }

    public function UpdateOrderStatus($orders_id, $status = 'Completed', $transaction_info = '') 
    {
        // Cập nhật trạng thái đơn hàng
        $query = "UPDATE orders SET status = ?, order_date = NOW() WHERE orders_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $orders_id);
        $stmt->execute();
        
        return $stmt->affected_rows > 0;
    }
}
