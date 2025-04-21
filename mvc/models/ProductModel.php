<?php

class ProductModel extends DB
{
    private $itemsPerPage = 20;

    public function GetAllCategories() {
        $query = "SELECT * FROM category ORDER BY category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function GetAllProduct()
    {
        $query = "SELECT * FROM Product";
        $results = mysqli_query($this->conn, $query);
        return $results->fetch_all(MYSQLI_ASSOC);
    }

    public function GetProducts($page = 1, $search = '', $category_id = null)
    {
        // Tính vị trí bắt đầu lấy dữ liệu
        $startFrom = ($page - 1) * $this->itemsPerPage;
        
        $query = "SELECT * FROM product WHERE 1=1";
        $params = array();
        $types = "";

        // Thêm điều kiện tìm kiếm nếu có
        if (!empty($search)) {
            $query .= " AND product_name LIKE ?";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $types .= "s";
        } 

        if (!empty($category_id)) {
            $query .= " AND category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }

        $query .= " ORDER BY product_id LIMIT ?, ?";  
        $params[] = $startFrom;  
        $params[] = $this->itemsPerPage;  
        $types .= "ii";  
    
        
        $stmt = $this->conn->prepare($query);
    
        if (!empty($params)) {
            // Sử dụng call_user_func_array để bind_param với mảng tham số
            $bind_params = array($stmt, $types);
            foreach ($params as $key => $value) {
                $bind_params[] = &$params[$key];
            }
            call_user_func_array('mysqli_stmt_bind_param', $bind_params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function GetTotalProducts($search = '', $category_id = null)
    {
        $query = "SELECT COUNT(*) as total FROM product WHERE 1=1";
        $params = array();
        $types = "";
        // Thêm điều kiện tìm kiếm nếu có
        if (!empty($search)) {
            $query .= " AND product_name LIKE ?";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $types .= "s";
        } 

        // Thêm điều kiện lọc theo category nếu có
        if (!empty($category_id)) {
            $query .= " AND category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }
        
        // Chuẩn bị và thực thi câu truy vấn
        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            // Sử dụng call_user_func_array để bind_param với mảng tham số
            $bind_params = array($stmt, $types);
            foreach ($params as $key => $value) {
                $bind_params[] = &$params[$key];
            }
            call_user_func_array('mysqli_stmt_bind_param', $bind_params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    public function GetTotalPages($search = '', $category_id = null)
    {
        $totalProducts = $this->GetTotalProducts($search, $category_id);
        return ceil($totalProducts / $this->itemsPerPage);
    }
}
