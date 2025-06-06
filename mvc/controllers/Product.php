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

    public function SearchSuggestions()
    {
        // Lấy từ khóa tìm kiếm và category nếu có
        $term = isset($_GET["term"]) ? $_GET["term"] : "";
        $category = isset($_GET["category"]) ? $_GET["category"] : "";
        
        // Set header JSON
        header('Content-Type: application/json');
        
        if (strlen($term) < 2) {
            echo json_encode([]);
            exit;
        }
        
        // Gọi model để tìm kiếm gợi ý
        $model = $this->model("ProductModel");
        $suggestions = $model->searchProductSuggestions($term, $category);
        
        // Trả về kết quả dưới dạng JSON
        echo json_encode($suggestions);
        exit;
    }

    public function Detail($id = null) {
        // Xử lý trường hợp $id là mảng
        if (is_array($id) && !empty($id)) {
            $id = $id[0];  // Lấy phần tử đầu tiên của mảng
        }
        
        error_log("Detail method called with processed ID: " . $id);
        
        if (!isset($id) || !is_numeric($id)) {
            error_log("ID not valid: " . $id);
            header('Location: /VNPay/Product/Show');
            return;
        }
        
        // Phần còn lại của phương thức giữ nguyên
        $productModel = $this->model("ProductModel");
        $product = $productModel->getProductById($id);
        
        error_log("Product data: " . json_encode($product));
        
        if (!$product) {
            error_log("Product not found for ID: " . $id);
            header('Location: /VNPay/Product/Show');
            return;
        }
        
        // Lấy các sản phẩm liên quan (cùng danh mục)
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id, 4);
        
        // Bỏ qua việc lấy thông tin danh mục hoặc xử lý nó trong ProductModel
        // $categoryModel = $this->model("CategoryModel");
        // $category = $categoryModel->getCategoryById($product['category_id']);
        
        // Hoặc có thể lấy danh mục từ ProductModel nếu nó đã có sẵn phương thức tương tự
        $category = isset($product['category_id']) ? 
            $productModel->getCategoryById($product['category_id']) : null;
        
        // Truyền dữ liệu sang view
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/Product/Detail",
            "Product" => $product,
            "Category" => $category,
            "RelatedProducts" => $relatedProducts,
            "Title" => $product['product_name']
        ]);
    }
}
