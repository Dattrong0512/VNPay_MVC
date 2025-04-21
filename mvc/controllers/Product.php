<?php
require_once("./mvc/core/Controller.php");
class Product extends Controller
{
    function Show($params = [])
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

        // Lấy trang hiện tại từ query parameter, mặc định là trang 1
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    
        if ($page < 1) $page = 1;
        
        $model = $this->model("ProductModel");
        $categories = $model->GetAllCategories();
        error_log("Number of categories: " . count($categories));
        $products = $model->GetProducts($page, $search, $category_id);
        $totalPages = $model->GetTotalPages($search, $category_id);
        
        // Truyền dữ liệu sang view
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/Product",
            "Model" => $products,
            "Categories" => $categories,
            "CurrentPage" => $page,
            "TotalPages" => $totalPages,
            "Search" => $search,
            "CategoryId" => $category_id
        ]);
    }
}
