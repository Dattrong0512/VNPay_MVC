<?php

class ProductModel extends DB
{
    public function GetAllProduct()
    {
        $query = "SELECT * FROM Product";
        $results = mysqli_query($this->conn, $query);
        return $results->fetch_all(MYSQLI_ASSOC);
    }
}
