<?php
require_once("./mvc/core/Controller.php");
class Product extends Controller
{
    function Show()
    {
        $model = $this->model("ProductModel");
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/Product", // Đường dẫn đến view Product
            "Model" => $model->GetAllProduct() // Dữ liệu sản phẩm
        ]);
    }
}
