<?php

class CartModel extends DB
{
    public function GetCart()
    {
        $query = "select  od.total_price, odd.orderdetail_id, product_name, product_price, odd.product_amount
                from
                orders od
                join orderdetail odd
                on od.orders_id = odd.orders_id
                join product pd
                on odd.product_id=pd.product_id
                where od.customer_id = 1;
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
}
