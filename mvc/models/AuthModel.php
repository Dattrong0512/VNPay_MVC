<?php
class AuthModel extends DB
{
    public function checkLogin($username, $password)
    {
        // Truy vấn kiểm tra đăng nhập
        $query = "SELECT customer_id, username, customer_name, customer_password, role_user, email 
                  FROM customer
                  WHERE username = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu (trong cơ sở dữ liệu hiện tại chưa hash)
            // Nếu đã hash, sử dụng: if (password_verify($password, $user['customer_password']))
            if ($password === $user['customer_password']) {
                // Loại bỏ mật khẩu trước khi trả về
                unset($user['customer_password']);
                return $user;
            }
        }
        
        return null;
    }
    
    public function registerUser($customer_name, $username, $password, $email)
    {
        // Kiểm tra username đã tồn tại chưa
        $checkQuery = "SELECT customer_id FROM customer WHERE username = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            return false; // Username đã tồn tại
        }
        
        // Thêm người dùng mới
        $query = "INSERT INTO customer (customer_name, auth_type, username, customer_password, role_user, email)
                  VALUES (?, 1, ?, ?, 2, ?)"; // auth_type = 1, role_user = 2 (người dùng thông thường)
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $customer_name, $username, $password, $email);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id; // Trả về ID của người dùng mới
        }
        
        return false;
    }
}