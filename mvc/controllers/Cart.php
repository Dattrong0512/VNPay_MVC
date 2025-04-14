<?php
require_once("./mvc/core/Controller.php");
class Cart extends Controller
{
    function Show()
    {
        $model = $this->model("CartModel");
        $this->view("Layout/MainLayout", [
            "Page" => "Pages/Cart", // Đường dẫn đến view Product
            "Model" => $model->GetCart() // Dữ liệu sản phẩm
        ]);
    }
    public function DeleteItem()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["orderdetail_id"])) {
            $orderdetail_id = $_POST["orderdetail_id"];
            $model = $this->model("CartModel");
            $isDeleted = $model->DeleteItem($orderdetail_id);

            if ($isDeleted) {
                // Xóa thành công, chuyển hướng về trang giỏ hàng
                header("Location: /VNPay/Cart/Show");
            } else {
                // Xóa thất bại, hiển thị thông báo lỗi
                echo "<p style='color: red; text-align: center;'>Xóa sản phẩm thất bại.</p>";
            }
        }
    }

    public function Payment()
    {
        
    }
}
