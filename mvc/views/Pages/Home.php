<h1 class="home-title">Chào mừng đến với Gym Shop</h1>
<p class="home-intro">Khám phá các sản phẩm chất lượng cao để hỗ trợ quá trình tập luyện của bạn!</p>

<!-- Form tìm kiếm sản phẩm -->
<div class="home-search-wrapper">
    <form action="/VNPay/Product/Show" method="GET" class="home-search-form">
        <div class="search-flex-container">
            <label for="search" class="home-search-label">Tìm kiếm sản phẩm:</label>
            <div class="home-search-input-container">
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    placeholder="Nhập tên sản phẩm..." 
                    class="home-search-input"
                >
                <button type="submit" class="home-search-button">
                    <i class="fa fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Banner chính -->
<div class="main-banner">
    <a href="/VNPay/Product/Show">
        <img src="/VNPay/public/images/banners/gym-banner.jpg" alt="Khuyến mãi đặc biệt" class="banner-image">
    </a>
</div>