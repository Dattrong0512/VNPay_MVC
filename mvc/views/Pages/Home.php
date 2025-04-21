<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Pages\Home.php -->
<h1 class="home-title">Chào mừng đến với Gym Shop</h1>
<p class="home-intro">Khám phá các sản phẩm chất lượng cao để hỗ trợ quá trình tập luyện của bạn!</p>

<!-- Form tìm kiếm sản phẩm -->
<div class="search-container">
    <form action="/VNPay/Product/Show" method="GET" class="search-form home-search-form">
        <div class="search-label-container">
            <label for="search" class="search-label">Tìm kiếm sản phẩm:</label>
        </div>
        <div class="search-input-container">
            <input 
                type="text" 
                id="search" 
                name="search" 
                placeholder="Nhập tên sản phẩm..." 
                class="search-input"
                required
            >
            <button type="submit" class="search-button">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
        </div>
    </form>
</div>

<!-- Banner chính -->
<div class="main-banner">
    <a href="/VNPay/Product/Show">
        <img src="/VNPay/public/images/banners/gym-banner.jpg" alt="Khuyến mãi đặc biệt" class="banner-image">
    </a>
</div>