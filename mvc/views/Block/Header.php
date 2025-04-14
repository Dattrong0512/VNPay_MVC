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
            font-size: 20px;
        }

        main {
            margin-top: 80px;
            /* Để tránh nội dung bị che bởi header fixed */
            flex: 1;
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
            <a href="/VNPay/Home">Gym Shop</a>
        </div>
        <div class="cart">
            <span class="cart-icon">🛒</span>
            <a href="/VNPay/Cart">Giỏ hàng</a>
        </div>
    </header>
    <main>
        <!-- Nội dung chính sẽ được thêm vào từ các view khác -->
    </main>
</body>

</html>