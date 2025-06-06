<?php

class CartModel extends DB
{
    public function GetCart($customer_id = null)
    {
        // Nếu không có customer_id được truyền vào, sử dụng mặc định là 3
        if ($customer_id === null) {
            $customer_id = 3;
        }
        
        $query = "SELECT od.total_price, odd.orderdetail_id, product_name, product_price, 
                odd.product_amount, od.orders_id, pd.product_id
                FROM orders od
                JOIN orderdetail odd ON od.orders_id = odd.orders_id
                JOIN product pd ON odd.product_id = pd.product_id
                WHERE od.customer_id = ? AND od.status = 'Incompleted'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
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
        // Kiểm tra xem đã có đơn hàng chưa hoàn thành không
        $checkOrder = "SELECT orders_id FROM orders WHERE customer_id = ? and status = 'Incompleted'";
        $stmt = $this->conn->prepare($checkOrder);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Biến để theo dõi xem có phải sản phẩm mới hay không
        $isNewProduct = true;
        
        if ($result->num_rows == 0) {
            // Nếu chưa có đơn hàng, tạo đơn hàng mới
            $createOrder = "INSERT INTO orders (customer_id, status, total_price) VALUES ( ?, 'Incompleted', 0)";
            $stmt = $this->conn->prepare($createOrder);
            $stmt->bind_param("i",$customer_id);
            $stmt->execute();
            
            if ($stmt->affected_rows <= 0) {
                return false; 
            }
            $orders_id = $stmt->insert_id;
        } else {
            // Nếu đã có đơn hàng, lấy orders_id
            $row = $result->fetch_assoc(); 
            $orders_id = $row['orders_id'];
        }
        
        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $checkItem = "SELECT orderdetail_id, product_amount FROM orderdetail 
                    WHERE orders_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($checkItem);
        $stmt->bind_param("ii", $orders_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Sản phẩm đã tồn tại, cập nhật số lượng
            $isNewProduct = false;
            $item = $result->fetch_assoc();
            $newAmount = $item["product_amount"] + $product_amount;
            
            $updateQuery = "UPDATE orderdetail SET product_amount = ? WHERE orderdetail_id = ?";
            $stmt = $this->conn->prepare($updateQuery);
            $stmt->bind_param("ii", $newAmount, $item["orderdetail_id"]);
            $stmt->execute();
            
            $success = $stmt->affected_rows > 0;
        } else {
            // Sản phẩm chưa tồn tại, thêm mới
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
        
        $result = new stdClass();
        $result->success = $success;
        $result->isNewProduct = $isNewProduct;
        return $result;
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

    public function GetCartItemCount($customer_id) {
        // Đếm số loại sản phẩm khác nhau trong giỏ hàng
        $query = "SELECT COUNT(odd.product_id) as item_count
                FROM orders od
                JOIN orderdetail odd ON od.orders_id = odd.orders_id
                WHERE od.customer_id = ? AND od.status = 'Incompleted'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['item_count'];
        }
        
        return 0;
    }
}
