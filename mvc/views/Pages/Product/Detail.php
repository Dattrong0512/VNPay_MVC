<div class="container product-detail-container">
    <?php if (isset($data["Product"])): ?>
        <?php 
        $product = $data["Product"]; 
        $productId = $product['product_id'];
        $productImg = "/VNPay/public/images/product/{$productId}.png";
        ?>
        
        <!-- Breadcrumb navigation -->
        <div class="breadcrumb">
            <a href="/VNPay/Home">Trang chủ</a> &raquo; 
            <a href="/VNPay/Product/Show">Sản phẩm</a> &raquo;
            <?php if (isset($data['Category'])): ?>
                <a href="/VNPay/Product/Show?category=<?php echo $data['Category']['category_id']; ?>"><?php echo htmlspecialchars($data['Category']['category_name']); ?></a> &raquo;
            <?php endif; ?>
            <span><?php echo htmlspecialchars($product['product_name']); ?></span>
        </div>
        
        <div class="product-detail-main">
            <div class="product-detail-image">
                <img src="<?php echo $productImg; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-main-image" onerror="this.src='/VNPay/public/images/product/default.png'">
            </div>
            
            <div class="product-detail-info">
                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                
                <div class="product-detail-meta">
                    <p class="product-detail-category">
                        <strong>Danh mục:</strong> 
                        <?php if (isset($data['Category'])): ?>
                            <a href="/VNPay/Product/Show?category=<?php echo $data['Category']['category_id']; ?>">
                                <?php echo htmlspecialchars($data['Category']['category_name']); ?>
                            </a>
                        <?php endif; ?>
                    </p>
                    
                    <?php if (!empty($product['product_provider'])): ?>
                        <p class="product-detail-provider">
                            <strong>Nhà sản xuất:</strong> 
                            <span><?php echo htmlspecialchars($product['product_provider']); ?></span>
                        </p>
                    <?php endif; ?>
                </div>
                
                <div class="product-detail-price">
                    <span class="price-label">Giá:</span>
                    <span class="price-value"><?php echo number_format($product['product_price'], 0, ',', '.'); ?> VND</span>
                </div>
                
                <form class="add-to-cart-form" onsubmit="addToCartDetail(event, <?php echo $product['product_id']; ?>)">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    
                    <div class="quantity-selector">
                        <label for="product_amount">Số lượng:</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="decrementQuantity()">-</button>
                            <input type="number" name="product_amount" id="product_amount" value="1" min="1" max="10" class="quantity-input">
                            <button type="button" class="quantity-btn" onclick="incrementQuantity()">+</button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary add-to-cart-btn">
                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </form>
            </div>
        </div>
        
        <div class="product-detail-tabs">
            <div class="tabs-header">
                <button class="tab-button active" onclick="openTab('description')">Mô tả</button>
                <button class="tab-button" onclick="openTab('details')">Chi tiết sản phẩm</button>
            </div>
            
            <div id="description" class="tab-content active">
                <div class="product-description">
                    <?php if (!empty($product['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <?php else: ?>
                        <p>Chưa có mô tả chi tiết cho sản phẩm này.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div id="details" class="tab-content">
                <table class="product-details-table">
                    <tr>
                        <th>Mã sản phẩm</th>
                        <td><?php echo $product['product_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Danh mục</th>
                        <td>
                            <?php if (isset($data['Category'])): ?>
                                <?php echo htmlspecialchars($data['Category']['category_name']); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Nhà sản xuất</th>
                        <td><?php echo htmlspecialchars($product['product_provider']); ?></td>
                    </tr>
                    <tr>
                        <th>Giá</th>
                        <td><?php echo number_format($product['product_price'], 0, ',', '.'); ?> VND</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <?php if (!empty($data['RelatedProducts'])): ?>
            <div class="related-products">
                <h2>Sản phẩm liên quan</h2>
                
                <div class="product-grid">
                    <?php foreach ($data['RelatedProducts'] as $relProduct): ?>
                        <?php 
                        $relProductId = $relProduct['product_id'];
                        $relProductImg = "/VNPay/public/images/product/{$relProductId}.png";
                        ?>
                        <div class="product-card">
                            <a href="/VNPay/Product/Detail/<?php echo $relProductId; ?>" class="product-link">
                                <div class="product-image-container">
                                    <img src="<?php echo $relProductImg; ?>" alt="<?php echo htmlspecialchars($relProduct['product_name']); ?>" class="product-image" onerror="this.src='/VNPay/public/images/product/default.png'">
                                </div>
                                <div class="product-info">
                                    <div class="product-title-container">
                                        <h3 class="product-title"><?php echo htmlspecialchars($relProduct['product_name']); ?></h3>
                                    </div>
                                    <p class="product-price"><?php echo number_format($relProduct['product_price'], 0, ',', '.'); ?> VND</p>
                                </div>
                            </a>
                            <div class="button-container">
                                <form class="add-to-cart-form" onsubmit="addToCartDetail(event, <?php echo $relProduct['product_id']; ?>)">
                                    <input type="hidden" name="product_id" value="<?php echo $relProduct['product_id']; ?>">
                                    <input type="hidden" name="product_amount" value="1">
                                    <button type="submit" class="btn btn-primary add-to-cart-btn">
                                        Thêm vào giỏ hàng
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="not-found">
            <h2>Không tìm thấy sản phẩm</h2>
            <p>Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
            <p><a href="/VNPay/Product/Show" class="btn btn-primary">Xem tất cả sản phẩm</a></p>
        </div>
    <?php endif; ?>
    
    <!-- Notification container -->
    <div id="notification" class="notification">
        <i class="fa fa-check-circle notification-icon"></i>
        <span class="notification-message">Đã thêm sản phẩm vào giỏ hàng</span>
    </div>
</div>

<script>
function openTab(tabId) {
    // Hide all tab contents
    const tabContents = document.getElementsByClassName('tab-content');
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove('active');
    }
    
    // Remove active class from all tab buttons
    const tabButtons = document.getElementsByClassName('tab-button');
    for (let i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove('active');
    }
    
    // Show the selected tab content and mark button as active
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

function incrementQuantity() {
    const quantityInput = document.getElementById('product_amount');
    const currentValue = parseInt(quantityInput.value, 10);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

function decrementQuantity() {
    const quantityInput = document.getElementById('product_amount');
    const currentValue = parseInt(quantityInput.value, 10);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function addToCartDetail(event, productId) {
    event.preventDefault();
    
    // Gọi API kiểm tra đăng nhập
    fetch('/VNPay/Cart/CheckLogin', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.loggedIn) {
            // Nếu đã đăng nhập, tiến hành thêm vào giỏ hàng
            const form = event.target;
            const formData = new FormData(form);
            
            // Hiển thị loading
            const button = form.querySelector('button');
            const originalText = button.innerHTML;
            button.disabled = true;
            
            fetch('/VNPay/Cart/AddToCartAjax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data); // Thêm log để debug
                
                if(data.success) {
                    // Thêm sản phẩm thành công
                    const quantityInput = document.getElementById('product_amount');
                    const quantity = parseInt(quantityInput.value, 10);
                    
                    // Lưu ý: chỉ cập nhật số lượng nếu là sản phẩm mới
                    updateCartCount(data.isNewProduct);
                    
                    // Hiển thị thông báo thành công
                    showNotification("Đã thêm sản phẩm vào giỏ hàng");
                    
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);
                } else {
                    // Xử lý khi có lỗi
                    alert('Có lỗi xảy ra: ' + (data.message || 'Không thể thêm sản phẩm'));
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                button.innerHTML = originalText;
                button.disabled = false;
            });
        } else {
            // Chưa đăng nhập, chuyển đến trang đăng nhập
            const currentUrl = window.location.pathname + window.location.search;
            window.location.href = '/VNPay/Auth/Show?redirect=' + encodeURIComponent(currentUrl);
        }
    })
    .catch(error => {
        console.error('Lỗi kiểm tra đăng nhập:', error);
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
    notification.style.width = 'auto';      
    notification.style.height = 'auto';  
    notification.classList.add('show');
    
    // Tự động ẩn thông báo sau thời gian đã định
    setTimeout(() => {
        notification.classList.remove('show');
    }, duration);
}

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng ở header
function updateCartCount(isNewProduct) {
    const cartCountElement = document.querySelector('.cart-count');
    
    if (cartCountElement && isNewProduct === true) {
        const currentCount = parseInt(cartCountElement.textContent, 10) || 0;
        cartCountElement.textContent = currentCount + 1;
        
        // Thêm hiệu ứng khi cập nhật số lượng
        cartCountElement.classList.add('update-animation');
        setTimeout(() => {
            cartCountElement.classList.remove('update-animation');
        }, 500);
    }
}



</script>

<style>
.product-detail-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 15px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    padding: 10px 0;
    margin-bottom: 20px;
    font-size: 14px;
    color: #666;
}

.breadcrumb a {
    color: #333;
    text-decoration: none;
    margin: 0 5px;
}

.breadcrumb a:first-child {
    margin-left: 0;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb span {
    margin: 0 5px;
    color: #888;
}

.product-detail-main {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    margin-bottom: 40px;
}

.product-detail-image {
    flex: 0 0 calc(50% - 20px);
    max-width: calc(50% - 20px);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f8f8;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.product-main-image {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
    transition: transform 0.3s;
}

.product-main-image:hover {
    transform: scale(1.03);
}

.product-detail-info {
    flex: 0 0 calc(50% - 20px);
    max-width: calc(50% - 20px);
}

.product-detail-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 20px;
    color: #222;
}

.product-detail-meta {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
    font-size: 18px;
}

.product-detail-category,
.product-detail-provider {
    margin: 8px 0;
    font-size: 18px;
}

.product-detail-category a {
    color: #007bff;
    text-decoration: none;
}

.product-detail-category a:hover {
    text-decoration: underline;
}

.product-detail-price {
    display: flex;
    align-items: center;
    margin: 20px 0;
    padding: 15px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.price-label {
    font-size: 18px;
    font-weight: 500;
    color: #333;
    margin-right: 10px;
}

.price-value {
    font-size: 28px;
    font-weight: 700;
    color: #e74c3c;
}

.quantity-selector {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.quantity-selector label {
    font-size: 16px;
    margin-right: 10px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: none;
    background-color: #f5f5f5;
    color: #333;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s;
}

.quantity-btn:hover {
    background-color: #e0e0e0;
}

.quantity-input {
    width: 60px;
    height: 40px;
    border: none;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    text-align: center;
    font-size: 16px;
}

.quantity-input:focus {
    outline: none;
}

.add-to-cart-btn {
    width: 100%;
    padding: 15px;
    font-size: 18px;
    font-weight: 600;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.add-to-cart-btn:hover {
    background-color: #c0392b;
}

.add-to-cart-btn:focus {
    outline: none;
}

.product-detail-tabs {
    margin-top: 40px;
}

.tabs-header {
    display: flex;
    border-bottom: 1px solid #ddd;
    margin-bottom: 20px;
}

.tab-button {
    padding: 12px 20px;
    background: none;
    border: none;
    font-size: 16px;
    font-weight: 600;
    color: #555;
    cursor: pointer;
    position: relative;
    transition: color 0.3s;
}

.tab-button:after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 3px;
    background: transparent;
    transition: background-color 0.3s;
}

.tab-button:hover {
    color: #222;
}

.tab-button.active {
    color: #e74c3c;
}

.tab-button.active:after {
    background-color: #e74c3c;
}

.tab-content {
    display: none;
    padding: 20px 0;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.5s;
}

.product-description {
    line-height: 1.7;
    color: #444;
    font-size: 18px
}

.product-details-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 18px;
}

.product-details-table th,
.product-details-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 18px;
}

.product-details-table th {
    width: 30%;
    font-weight: 600;
    color: #333;
    font-size: 18px;
}

.related-products {
    margin-top: 60px;
}

.related-products h2 {
    font-size: 24px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #333;
}

.not-found {
    text-align: center;
    padding: 40px 0;
}

.not-found h2 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

.not-found p {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.notification {
    position: fixed;
    top: 70px;             /* Thay đổi từ bottom: 20px thành top: 70px */
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease-in-out;
    z-index: 1000;
    transform: translateY(-20px); /* Thay đổi từ translateY(20px) */
    width: auto;
    max-width: 350px;
    margin: 0;
    left: auto;
    bottom: auto;          /* Thêm dòng này để đảm bảo không bị ảnh hưởng bởi bottom */
}

.notification.show {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
}

.notification-icon {
    font-size: 20px;
    margin-right: 10px;
}

/* Responsive design */
@media (max-width: 992px) {
    .product-detail-main {
        flex-direction: column;
    }
    
    .product-detail-image,
    .product-detail-info {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .product-detail-image {
        margin-bottom: 30px;
    }
    
    .related-products .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .related-products .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .product-detail-title {
        font-size: 24px;
    }
    
    .price-value {
        font-size: 24px;
    }
    
    .tabs-header {
        flex-direction: column;
        border-bottom: none;
    }
    
    .tab-button {
        width: 100%;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    .tab-button:after {
        display: none;
    }
    
    .related-products .product-grid {
        grid-template-columns: 1fr;
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.related-products .product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.related-products .product-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.related-products .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.related-products .product-link {
    display: block;
    text-decoration: none;
    color: inherit;
    flex: 1;
}

.related-products .product-image-container {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    padding: 10px;
}

.related-products .product-image {
    max-height: 180px;
    max-width: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.related-products .product-link:hover .product-image {
    transform: scale(1.05);
}

.related-products .product-info {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.related-products .product-title-container {
    flex: 1;
    margin-bottom: 10px;
}

.related-products .product-title {
    font-size: 16px;
    font-weight: 500;
    margin: 0;
    color: #333;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.related-products .product-link:hover .product-title {
    color: #e74c3c;
}

.related-products .product-price {
    font-weight: 700;
    font-size: 18px;
    color: #e74c3c;
    margin: 5px 0;
}

.related-products .button-container {
    padding: 0 15px 15px;
}

.related-products .add-to-cart-btn {
    width: 100%;
    padding: 12px;
    font-size: 14px;
    background-color: #e74c3c;
    border: none;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
}

.related-products .add-to-cart-btn:hover {
    background-color: #c0392b;
}

/* Responsive cho sản phẩm liên quan */
@media (max-width: 992px) {
    .related-products .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .related-products .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .related-products .product-grid {
        grid-template-columns: 1fr;
    }
}
</style>
