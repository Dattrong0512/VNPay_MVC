<h1 class="page-title">Danh sách sản phẩm</h1>

<!-- Thêm style cho animation -->
<style>
.product-card {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.product-card.show {
    opacity: 1;
    transform: translateY(0);
}

/* Tạo hiệu ứng staggered cho từng sản phẩm */
<?php if (isset($data["Model"]) && is_array($data["Model"])): ?>
    <?php $delay = 0; foreach ($data["Model"] as $index => $product): ?>
        .product-card:nth-child(<?php echo $index + 1; ?>) {
            transition-delay: <?php echo $delay; ?>s;
        }
        <?php $delay += 0.05; /* Tăng delay cho mỗi sản phẩm */ ?>
    <?php endforeach; ?>
<?php endif; ?>
</style>


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
            <div class="search-autocomplete-container">
                <input 
                    type="text" 
                    name="search" 
                    id="search-input"
                    placeholder="Tìm kiếm theo tên sản phẩm..." 
                    class="search-input"
                    value="<?php echo isset($data['Search']) ? htmlspecialchars($data['Search']) : ''; ?>"
                    autocomplete="off"
                >
                <div class="search-suggestions" id="search-suggestions"></div>
            </div>
            
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
                <a href="/VNPay/Product/Detail/<?php echo $product['product_id']; ?>" class="product-link">
                    <div class="product-image-container">
                        <img src="<?php echo $productImg; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="product-image" onerror="this.src='/VNPay/public/images/product/default.png'">
                    </div>
                    <div class="product-info">
                        <div class="product-title-container">
                            <h3 class="product-title"><?php echo htmlspecialchars($product["product_name"]); ?></h3>
                        </div>
                        <p class="product-price"><?php echo number_format($product["product_price"], 0, ',', '.'); ?> VND</p>
                    </div>
                </a>
                <div class="button-container">
                    <form class="add-to-cart-form" onsubmit="addToCart(event, <?php echo $product['product_id']; ?>)">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="product_amount" value="1">
                        
                        <!-- Giữ thông tin tìm kiếm và danh mục giữ nguyên -->
                        <?php if (!empty($data['Search'])): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['Search']); ?>">
                        <?php endif; ?>
                        
                        <?php if (!empty($data['CategoryId'])): ?>
                            <input type="hidden" name="category" value="<?php echo $data['CategoryId']; ?>">
                        <?php endif; ?>
                        
                        <button type="submit" class="btn btn-primary add-to-cart-btn">
                            Thêm vào giỏ hàng
                        </button>
                    </form>
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

<style>
.loading-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    width: 100%;
    display: none;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(0, 0, 0, 0.1);
    border-top-color: #e74c3c;
    border-radius: 50%;
    animation: spin 1s ease-in-out infinite;
    margin-bottom: 15px;
}

.product-link {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s ease;
}

.product-link:hover .product-image {
    transform: scale(1.05);
}

.product-link:hover .product-title {
    color: #e74c3c;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Animation cho fade-in sản phẩm */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<!-- Thêm vào cuối file, trước khi đóng tag </div> cuối cùng -->

<script>
function addToCart(event, productId) {
    event.preventDefault();
    
    // Gọi API kiểm tra đăng nhập trước khi thêm vào giỏ hàng
    fetch('/VNPay/Cart/CheckLogin', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.loggedIn) {
            // Nếu đã đăng nhập, tiến hành thêm vào giỏ hàng
            processAddToCart(event.target, productId);
        } else {
            // Nếu chưa đăng nhập, lưu vị trí hiện tại và chuyển đến trang đăng nhập
            const currentUrl = window.location.pathname + window.location.search;
            window.location.href = '/VNPay/Auth/Show?redirect=' + encodeURIComponent(currentUrl);
        }
    })
    .catch(error => {
        console.error('Lỗi kiểm tra đăng nhập:', error);
    });
}

function processAddToCart(form, productId) {
    const formData = new FormData(form);
    
    // Hiển thị loading
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
        console.log('Response data:', data);  // Debug để xem dữ liệu trả về
        
        if(data.success) {
            // Cập nhật số lượng trong giỏ hàng, chỉ khi là sản phẩm mới
            updateCartCount(data.isNewProduct);
            
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

// Thay đổi hàm updateCartCount để xử lý đúng việc thêm sản phẩm mới
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
<script>
// Thêm vào đầu file script hoặc giữ nguyên các hàm hiện có và thêm hàm mới
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionContainer = document.getElementById('search-suggestions');
    let typingTimer;
    const doneTypingInterval = 300; // ms

    // Sự kiện khi người dùng gõ
    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        
        if (searchInput.value.length > 1) {
            typingTimer = setTimeout(fetchSuggestions, doneTypingInterval);
        } else {
            suggestionContainer.innerHTML = '';
            suggestionContainer.classList.remove('active');
        }
    });

    // Sự kiện khi focus vào input
    searchInput.addEventListener('focus', function() {
        if (suggestionContainer.innerHTML !== '' && searchInput.value.length > 1) {
            suggestionContainer.classList.add('active');
        }
    });

    // Sự kiện khi click ra ngoài
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !suggestionContainer.contains(event.target)) {
            suggestionContainer.classList.remove('active');
        }
    });

    // Hàm gọi API để lấy đề xuất
    function fetchSuggestions() {
        const searchTerm = searchInput.value.trim();
        
        if (searchTerm.length < 2) return;
        
        // Hiển thị loading
        suggestionContainer.innerHTML = '<div class="suggestion-loading">Đang tìm kiếm...</div>';
        suggestionContainer.classList.add('active');
        
        // Lấy category hiện tại nếu có
        const categorySelect = document.getElementById('category');
        const categoryId = categorySelect ? categorySelect.value : '';
        
        // Gọi API tìm kiếm
        fetch(`/VNPay/Product/SearchSuggestions?term=${encodeURIComponent(searchTerm)}&category=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                suggestionContainer.innerHTML = '';
                
                if (data.length > 0) {
                    data.forEach(product => {
                        const imgSrc = `/VNPay/public/images/product/${product.product_id}.png`;
                        const defaultImg = '/VNPay/public/images/product/default.png';
                        
                        const item = document.createElement('div');
                        item.className = 'suggestion-item';
                        item.innerHTML = `
                            <img src="${imgSrc}" class="suggestion-image" onerror="this.src='${defaultImg}'">
                            <div class="suggestion-info">
                                <div class="suggestion-name">${product.product_name}</div>
                                <div class="suggestion-price">${formatPrice(product.product_price)} VND</div>
                            </div>
                        `;
                        
                        item.addEventListener('click', function() {
                            window.location.href = `/VNPay/Product/Detail/${product.product_id}`;
                        });
                        
                        suggestionContainer.appendChild(item);
                    });
                } else {
                    suggestionContainer.innerHTML = '<div class="no-suggestions">Không tìm thấy sản phẩm phù hợp</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
                suggestionContainer.innerHTML = '<div class="no-suggestions">Đã xảy ra lỗi khi tìm kiếm</div>';
            });
    }
    
    // Hàm định dạng giá tiền
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ẩn loading indicator
    const loadingIndicator = document.getElementById('loading-indicator');
    
    // Hiệu ứng show sản phẩm
    function showProducts() {
        const productCards = document.querySelectorAll('.product-card');
        
        // Hiển thị các sản phẩm sau khi trang đã load xong
        setTimeout(function() {
            productCards.forEach(card => {
                card.classList.add('show');
            });
        }, 100);
    }
    
    // Khi trang load xong thì ẩn loading và hiển thị sản phẩm
    window.onload = function() {
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        showProducts();
    };
    
    // Nếu trang đã load xong trước khi event listener được gắn
    if (document.readyState === 'complete') {
        showProducts();
    }
    
    // Khi chuyển trang bằng phân trang hoặc lọc
    const pageLinks = document.querySelectorAll('.page-link, .category-select, .search-form');
    pageLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Đối với select, chỉ trigger khi thực sự thay đổi
            if (link.classList.contains('category-select') && !link.dataset.changed) {
                return;
            }
            
            // Đối với form tìm kiếm, chỉ xử lý khi submit
            if (link.classList.contains('search-form')) {
                return;
            }
            
            // Hiển thị loading khi chuyển trang
            const productGrid = document.querySelector('.product-grid');
            if (productGrid) {
                productGrid.style.opacity = '0.5';
                loadingIndicator.style.display = 'flex';
            }
        });
    });
    
    // Đánh dấu khi select thay đổi
    const categorySelect = document.querySelector('.category-select');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            this.dataset.changed = 'true';
        });
    }
    
    // Animation cho lọc và tìm kiếm
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const productGrid = document.querySelector('.product-grid');
            if (productGrid) {
                productGrid.style.opacity = '0.5';
                loadingIndicator.style.display = 'flex';
            }
        });
    }
});
</script>