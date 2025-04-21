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
            background-color: #222;
            color: #fff;
            padding: 15px 40px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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

        .cart {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .cart a:hover {
            color: #f0c14b;
        }

        .cart-icon {
            font-size: 24px;
        }

        main {
            margin-top: 80px;
            /* Để tránh nội dung bị che bởi header fixed */
            flex: 1;
        }
        
        .cart-container {
            position: relative;
            display: inline-block;
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

            .cart {
                gap: 10px;
            }

            .cart a {
                font-size: 14px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="logo">
            <a href="/VNPay/Home">Gymilize</a>
        </div>
        <div class="cart">
            <?php
            require_once("./mvc/models/CartModel.php");
            require_once("./mvc/core/DB.php");

            $cartModel = new CartModel();
            $cartItemCount = 0;
            $cart = $cartModel->GetCart();
            if (is_array($cart)) {
                $cartItemCount = count($cart);
            }
            ?>
            <div class="cart">
                <a href="/VNPay/Cart/Show" class="cart-container">  <!-- Liên kết 1: Icon với số lượng -->
                    <span class="cart-icon">🛒</span>
                    <span class="cart-count" id = "cart-count"><?php echo $cartItemCount ;?></span>
                </a>
                <a href="/VNPay/Cart/Show">Giỏ hàng</a>  <!-- Liên kết 2: Chỉ có chữ -->
            </div>
        </div>
    </header>
    <main>
        <!-- Nội dung chính sẽ được thêm vào từ các view khác -->
    </main>
</body>

</html>