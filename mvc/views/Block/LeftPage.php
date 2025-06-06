<?php
// Khởi tạo session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div style="background-color: #f4f4f4; padding: 20px; width: 250px; height: 100%; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column;">
    <div class="menu-header" style="margin-bottom: 25px;">
        <h3 style="font-size: 25px; color: #333; border-bottom: 2px solid #e67e22; padding-bottom: 10px;">Danh mục</h3>
    </div>
    
    <ul style="list-style: none; padding: 0; flex-grow: 0;">
        <li style="margin-bottom: 15px;">
            <a href="/VNPay/Product" style="text-decoration: none; color: #333; font-size: 20px; transition: color 0.3s ease; display: flex; align-items: center;">
                <span style="font-size: 24px; margin-right: 10px;">🛒</span>
                <span>Sản phẩm</span>
            </a>
        </li>
        
        <!-- Mục lịch sử mua hàng -->
        <li style="margin-bottom: 15px;">
            <a href="<?php echo isset($_SESSION['is_logged_in']) ? '/VNPay/OrderHistory' : 'javascript:void(0);'; ?>" 
               style="text-decoration: none; color: #333; font-size: 20px; transition: color 0.3s ease; display: flex; align-items: center;"
               <?php if(!isset($_SESSION['is_logged_in'])): ?>onclick="openLoginPage('/VNPay/OrderHistory'); return false;"<?php endif; ?>>
                <span style="font-size: 24px; margin-right: 10px;">📜</span>
                <span>Lịch sử mua hàng</span>
            </a>
        </li>
        
        <!-- Nếu đăng nhập thì hiện thêm thông tin tài khoản -->
        <?php if(isset($_SESSION['is_logged_in'])): ?>
        <li style="margin-bottom: 15px;">
            <a href="/VNPay/Profile" style="text-decoration: none; color: #333; font-size: 20px; transition: color 0.3s ease; display: flex; align-items: center;">
                <span style="font-size: 24px; margin-right: 10px;">👤</span>
                <span>Thông tin tài khoản</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    
    <!-- Thêm phần quảng cáo hoặc banner dưới menu -->
    <div class="sidebar-promo" style="margin-top: auto; flex-grow: 1; display: flex; flex-direction: column; justify-content: flex-start;">
        <div class="promo-box" style="background-color: #fff; border-radius: 8px; padding: 15px; margin-top: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h4 style="color: #e67e22; font-size: 18px; margin-bottom: 10px;">Mừng khai trương</h4>
            <p style="color: #555; font-size: 14px; margin-bottom: 15px;">Chi nhánh MusclePlus đã được mở tại TPHCM với vô vàn ưu đãi hấp dẫn</p>
            <?php if(!isset($_SESSION['is_logged_in'])): ?>
            <p style="color: #777; font-size: 13px; font-style: italic;">Đến và mua sắm ngay hôm nay!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        ul li a:hover {
            color: #e67e22 !important;
        }
        
        .promo-box a:hover {
            background-color: #d35400;
        }
    </style>
    
    <script>
    function openLoginPage(redirect) {
        // Mở trang đăng nhập với tham số redirect
        window.location.href = '/VNPay/Auth/Show?redirect=' + encodeURIComponent(redirect);
    }
    </script>
</div>