<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Layout\MainLayout.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "/VNPay/public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Gym Shop</title>
</head>
<body>
    <!-- Nhúng Header -->
    <?php require_once "./mvc/views/Block/Header.php"; ?>

    <!-- Bố cục chính -->
    <div style="display: flex; min-height: 100vh;">
        <!-- Nhúng LeftPage -->
        <div style="width: 250px; background-color: #f4f4f4;">
            <?php require_once "./mvc/views/Block/LeftPage.php"; ?>
        </div>

        <!-- Nội dung chính -->
        <!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Layout\MainLayout.php -->
        <div class="content" style="flex: 1; padding: 20px;">
            <?php
            // Nội dung của từng trang sẽ được render tại đây
            if (isset($data["Page"])) {
                require_once "./mvc/views/" . $data["Page"] . ".php";
            }
            ?>
        </div>
    </div>

    <!-- Nhúng Footer -->
    <?php require_once "./mvc/views/Block/Footer.php"; ?>

    <script>
        function updateCartCount(change = 0) {
            const cartCountElement = document.getElementById('cart-count');
            if (!cartCountElement) return;
            
            let currentCount = parseInt(cartCountElement.textContent || '0');
            
            // Nếu có thay đổi (thêm/xóa sản phẩm)
            if (change !== 0) {
                currentCount += change;
                if (currentCount < 0) currentCount = 0;
                cartCountElement.textContent = currentCount;
                
                // Hiệu ứng nhấp nháy khi thay đổi
                cartCountElement.classList.add('update-animation');
                setTimeout(() => {
                    cartCountElement.classList.remove('update-animation');
                }, 500);
            }
        }
    </script>
</body>
</html>