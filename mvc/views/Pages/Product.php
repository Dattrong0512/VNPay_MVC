<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Pages\Product.php -->
<h1 style="text-align: center; margin-bottom: 20px;">Danh sách sản phẩm</h1>
<div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
    <?php
    if (isset($data["Model"]) && is_array($data["Model"])) {
        foreach ($data["Model"] as $product) {
            ?>
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; text-align: center;">
                <h3 style="font-size: 18px; color: #333;"><?php echo $product["product_name"]; ?></h3>
                <p style="color: #666; font-size: 14px;">Nhà cung cấp: <?php echo $product["product_provider"]; ?></p>
                <p style="color: #666; font-size: 14px;"><?php echo $product["description"]; ?></p>
                <p style="color: #e67e22; font-size: 16px; font-weight: bold;"><?php echo number_format($product["product_price"], 0, ',', '.'); ?> VND</p>
                <button style="padding: 10px 20px; background-color: #333; color: #fff; border: none; cursor: pointer; border-radius: 5px;"
                name = "AddtoCart">
                    Thêm vào giỏ hàng
                </button>
            </div>
            <?php
        }
    } else {
        echo "<p>Không có sản phẩm nào để hiển thị.</p>";
    }
    ?>
</div>