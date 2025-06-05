<?php
// Khởi tạo session trước khi xuất bất kỳ nội dung HTML nào
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: linear-gradient(135deg, #2c3e50 0%, #2c3e50 15%, #1c2833 50%, #0b0d0f 85%, #000000 100%);
            position: relative;
            color: #fff;
            padding: 15px 40px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        /* Hiệu ứng highlight tinh tế */
        header:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(66, 134, 244, 0.15), transparent);
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .logo a {
            color: #fff;
            text-decoration: none;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-right a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .header-right a:hover {
            color: #f0c14b;
        }

        .cart-container {
            position: relative;
            display: inline-block;
        }

        .cart-icon {
            font-size: 24px;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #f0c14b;
            color: #222;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .username {
            color: #fff;
            font-size: 16px;
        }

        .avatar {
            font-size: 20px;
        }

        main {
            margin-top: 80px;
            /* Để tránh nội dung bị che bởi header fixed */
            flex: 1;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

        .update-animation {
            animation: pulse 0.5s ease-in-out;
            background-color: #ff6b6b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 10px 20px;
            }

            .logo {
                font-size: 22px;
            }

            .header-right {
                gap: 10px;
            }

            .header-right a {
                font-size: 14px;
            }
            
            .username {
                display: none;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="logo">
            <a href="/VNPay/Home">MusclePlus</a>
        </div>
        <div class="header-right">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // Tính toán số lượng sản phẩm trong giỏ hàng
            require_once("./mvc/core/DB.php");
            require_once("./mvc/models/CartModel.php");
            $cartModel = new CartModel();
            
            $cartItemCount = 0;
            $customer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $cart = $cartModel->GetCart($customer_id);
            
            if (is_array($cart)) {
                $cartItemCount = count($cart);
            }
            ?>
            
            <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
                <!-- Nút đăng xuất cho người dùng đã đăng nhập -->
                <a href="/VNPay/Auth/Logout">
                    <span>Đăng xuất</span>
                </a>
            <?php else: ?>
                    <!-- Nút đăng nhập cho người dùng chưa đăng nhập - mở trang login với overlay -->
                    <a href="javascript:void(0);" onclick="openLoginPage()">
                        <span>Đăng nhập</span>
                    </a>
            <?php endif; ?>
            
            <!-- Biểu tượng giỏ hàng không có chữ, chỉ có icon -->
            <a href="<?php echo isset($_SESSION['is_logged_in']) ? '/VNPay/Cart/Show' : 'javascript:void(0);'; ?>" class="cart-container" 
            <?php if(!isset($_SESSION['is_logged_in'])): ?>onclick="openLoginPage(); return false;"<?php endif; ?>>
                <span class="cart-icon">🛒</span>
                <?php if (isset($_SESSION['is_logged_in'])): ?>
                    <span class="cart-count" id="cart-count"><?php echo $cartItemCount; ?></span>
                <?php endif; ?>
            </a>
            
            <!-- Thông tin người dùng và avatar ở bên phải cùng -->
            <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
                <div class="user-profile">
                    <span class="username">            
                        <?php 
                        if (isset($_SESSION['customer_name']) && !empty($_SESSION['customer_name'])) {
                            echo htmlspecialchars($_SESSION['customer_name']);
                        } else if (isset($_SESSION['username'])) {
                            echo htmlspecialchars($_SESSION['username']);
                        } else {
                            echo "Người dùng";
                        }
                        ?>
                    </span>
                    <div class="avatar">👤</div>
                </div>
            <?php endif; ?>

            <script>
            function openLoginPage() {
                // Lưu URL hiện tại để quay lại sau khi đăng nhập
                const currentUrl = window.location.pathname + window.location.search;
                
                // Mở trang đăng nhập với tham số redirect
                window.location.href = '/VNPay/Auth/Show?redirect=' + encodeURIComponent(currentUrl);
            }
            </script>
        </div>
    </header>
    <main>
        <!-- Nội dung chính sẽ được thêm vào từ các view khác -->
    </main>
</body>



</html>