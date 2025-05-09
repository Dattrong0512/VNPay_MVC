<h1 class="page-title">Danh sách sản phẩm</h1>

<!-- Thêm container cho thông báo -->
<div id="notification" class="notification">
    <i class="fa fa-check-circle notification-icon"></i>
    <span class="notification-message">Đã thêm sản phẩm vào giỏ hàng</span>
</div>

<!-- Thêm search bar -->
<div class="filter-container">
    <!-- Thanh tìm kiếm -->
    <div class="search-container">
        <form action="/VNPay/Product/Show" method="GET" class="search-form">
            <input 
                type="text" 
                name="search" 
                placeholder="Tìm kiếm theo tên sản phẩm..." 
                class="search-input"
                value="<?php echo isset($data['Search']) ? htmlspecialchars($data['Search']) : ''; ?>"
            >
            
            <!-- Nếu có category được chọn, giữ lại trong form -->
            <?php if (isset($data['CategoryId']) && !empty($data['CategoryId'])): ?>
                <input type="hidden" name="category" value="<?php echo $data['CategoryId']; ?>">
            <?php endif; ?>
            
            <button type="submit" class="search-button">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
        </form>
    </div>
    
    <!-- Dropdown chọn danh mục -->
    <div class="category-filter">
        <form action="/VNPay/Product/Show" method="GET" id="categoryForm" class="category-form">
            <label for="category">Phân loại:</label>
            <select name="category" id="category" class="category-select" onchange="this.form.submit()">
                <option value="">Tất cả sản phẩm</option>
                <?php foreach ($data['Categories'] as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>" 
                        <?php echo (isset($data['CategoryId']) && $data['CategoryId'] == $category['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <!-- Nếu có search term, giữ lại trong form -->
            <?php if (isset($data['Search']) && !empty($data['Search'])): ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['Search']); ?>">
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Hiển thị thông tin kết quả tìm kiếm -->
<?php if (!empty($data['Search'])): ?>
    <div class="search-result-info">
        Kết quả tìm kiếm cho: <strong><?php echo htmlspecialchars($data['Search']); ?></strong>
        <a href="<?php echo !empty($data['CategoryId']) ? '/VNPay/Product/Show?category=' . $data['CategoryId'] : '/VNPay/Product/Show'; ?>" class="clear-search">Xóa tìm kiếm</a>
    </div>
<?php endif; ?>

<div class="product-grid">
    <?php
    if (isset($data["Model"]) && is_array($data["Model"]) && count($data["Model"]) > 0) {
        foreach ($data["Model"] as $product) {
            $productId = $product['product_id'];
            $productImg = "/VNPay/public/images/product/{$productId}.png";
            ?>
            <div class="product-card">
                <div class="product-image-container">
                    <img src="<?php echo $productImg; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image" onerror="this.src='/VNPay/public/images/product/default.png'">
                </div>
                <div class="product-info">
                    <div class="product-title-container">
                        <h3 class="product-title"><?php echo $product["product_name"]; ?></h3>
                    </div>
                    <p class="product-price"><?php echo number_format($product["product_price"], 0, ',', '.'); ?> VND</p>
                    <!-- Thay thế form hiện tại với form có event JavaScript -->
                    <div class="button-container">
                        <form class="add-to-cart-form" onsubmit="addToCart(event, <?php echo $product['product_id']; ?>)">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="product_amount" value="1">
                            
                            <!-- Giữ thông tin tìm kiếm -->
                            <?php if (!empty($data['Search'])): ?>
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['Search']); ?>">
                            <?php endif; ?>
                            
                            <!-- Giữ thông tin danh mục đã lọc -->
                            <?php if (!empty($data['CategoryId'])): ?>
                                <input type="hidden" name="category" value="<?php echo $data['CategoryId']; ?>">
                            <?php endif; ?>
                            
                            <button type="submit" class="btn btn-primary add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        // Kiểm tra xem có đang tìm kiếm hay không
        if (!empty($data['Search'])) {
            echo '<div class="no-results-container">
                    <div class="no-results-message">
                        <i class="fa fa-search fa-3x"></i>
                        <h3>Không tìm thấy sản phẩm</h3>
                        <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "<strong>' . htmlspecialchars($data['Search']) . '</strong>"</p>
                        <p>Vui lòng thử lại với từ khóa khác hoặc <a href="/VNPay/Product/Show">xem tất cả sản phẩm</a></p>
                    </div>
                </div>';
        } else {
            echo '<div class="no-results-container">
                    <div class="no-results-message">
                        <i class="fa fa-info-circle fa-3x"></i>
                        <h3>Không có sản phẩm</h3>
                        <p>Hiện tại chưa có sản phẩm nào để hiển thị.</p>
                    </div>
                </div>';
        }
    }
    ?>
</div>

<?php if (isset($data["TotalPages"]) && $data["TotalPages"] > 1): ?>
    <div class="pagination">
        <?php 
        $currentPage = $data["CurrentPage"];
        $totalPages = $data["TotalPages"];
        $search = isset($data["Search"]) ? $data["Search"] : '';
        $category = isset($data["CategoryId"]) ? $data["CategoryId"] : '';

        // Xây dựng base URL với tham số tìm kiếm
        $baseUrl = "/VNPay/Product/Show";
        $queryParams = [];
        
        if (!empty($search)) {
            $queryParams[] = "search=" . urlencode($search);
        }
        
        if (!empty($category)) {
            $queryParams[] = "category=" . $category;
        }

        if (!empty($queryParams)) {
            $baseUrl .= "?" . implode("&", $queryParams);
            // Nếu đã có tham số, thêm & để nối với page
            $baseUrl .= "&";
        } else {
            // Nếu không có tham số khác, bắt đầu với ?
            $baseUrl .= "?";
        }
        
        
        $queryString = implode("&", $queryParams);
        if (!empty($queryString)) {
            $baseUrl .= $queryString . "&";
        }

        // Nút Previous
        if ($currentPage > 1): ?>
            <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage - 1; ?>" class="page-link prev">&laquo;</a>
        <?php endif; ?>
        
        <!-- Hiển thị số trang -->
        <?php
        // Hiển thị tối đa 5 nút số trang
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $startPage + 4);
        
        if ($startPage > 1): ?>
            <a href="<?php echo $baseUrl; ?>page=1" class="page-link">1</a>
            <?php if ($startPage > 2): ?>
                <span class="page-dots">...</span>
            <?php endif; ?>
        <?php endif;
        
        for ($i = $startPage; $i <= $endPage; $i++): ?>
            <a href="<?php echo $baseUrl; ?>page=<?php echo $i; ?>" 
               class="page-link <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor;
        
        if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
                <span class="page-dots">...</span>
            <?php endif; ?>
            <a href="<?php echo $baseUrl; ?>page=<?php echo $totalPages; ?>" class="page-link"><?php echo $totalPages; ?></a>
        <?php endif; ?>
        
        <!-- Nút Next -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo $baseUrl; ?>page=<?php echo $currentPage + 1; ?>" class="page-link next">&raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Thêm vào cuối file, trước khi đóng tag </div> cuối cùng -->

<script>
function addToCart(event, productId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Hiển thị loading nếu muốn
    const button = form.querySelector('button');
    const originalText = button.textContent;
    button.textContent = "Đang thêm...";
    button.disabled = true;
    
    fetch('/VNPay/Cart/AddToCartAjax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Cập nhật số lượng trong giỏ hàng
            updateCartCount(1);
            
            // Hiển thị thông báo thành công
            showNotification("Đã thêm sản phẩm vào giỏ hàng");
            
            button.textContent = "Đã thêm";
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 1000);
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
            button.textContent = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        button.textContent = originalText;
        button.disabled = false;
    });
}

// Hàm hiển thị thông báo
function showNotification(message, duration = 3000) {
    const notification = document.getElementById('notification');
    
    // Cập nhật nội dung thông báo
    const messageElement = notification.querySelector('.notification-message');
    if (messageElement) {
        messageElement.textContent = message;
    }
    
    // Hiển thị thông báo
    notification.classList.add('show');
    
    // Tự động ẩn thông báo sau thời gian đã định
    setTimeout(() => {
        notification.classList.remove('show');
    }, duration);
}
</script>