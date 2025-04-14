<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Pages\Home.php -->
<h1>Chào mừng đến với Gym Shop</h1>
<p>Khám phá các sản phẩm chất lượng cao để hỗ trợ quá trình tập luyện của bạn!</p>

<!-- Form tìm kiếm sản phẩm -->
<form action="/VNPay/Home/Search" method="POST" style="margin-top: 20px;">
    <label for="search" style="font-size: 18px;">Tìm kiếm sản phẩm:</label>
    <input type="text" id="search" name="search" placeholder="Nhập tên sản phẩm..." style="padding: 10px; width: 300px; margin-left: 10px;">
    <button type="submit" style="padding: 10px 20px; background-color: #333; color: #fff; border: none; cursor: pointer;">Tìm kiếm</button>
</form>