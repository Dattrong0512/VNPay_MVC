<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Home.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Supplements Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .product img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
        }

        .product-info h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .product-info p {
            margin: 0 0 5px;
            color: #666;
        }

        .product-info .price {
            font-size: 18px;
            font-weight: bold;
            color: #e67e22;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Gym Supplements Store</h1>
    </header>
    <div class="container">
        <div class="product">
            <img src="https://via.placeholder.com/150" alt="Whey Protein">
            <div class="product-info">
                <h3>Whey Protein</h3>
                <p>High-quality protein to support muscle growth.</p>
                <p class="price">$50</p>
            </div>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/150" alt="Creatine">
            <div class="product-info">
                <h3>Creatine</h3>
                <p>Boost strength and performance during workouts.</p>
                <p class="price">$30</p>
            </div>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/150" alt="BCAA">
            <div class="product-info">
                <h3>BCAA</h3>
                <p>Essential amino acids for muscle recovery.</p>
                <p class="price">$25</p>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Gym Supplements Store. All rights reserved.</p>
    </footer>
</body>

</html>