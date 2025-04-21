<div class="order-history-container">
    <h1 class="page-title">Lịch sử đơn hàng</h1>
    
    <?php if (!empty($data["Orders"])): ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Danh sách sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data["Orders"] as $order): ?>
                    <tr>
                        <td><?php echo $order["orders_id"]; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($order["order_date"])); ?></td>
                        <td>
                            <ul class="product-list">
                                <?php foreach ($order["products"] as $product): ?>
                                    <li>
                                        <?php echo $product["product_name"]; ?> x <?php echo $product["product_amount"]; ?> 
                                        (<?php echo number_format($product["product_price"], 0, ',', '.'); ?> VND)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?php echo number_format($order["total_price"], 0, ',', '.'); ?> VND</td>
                        <td>
                            <span class="status-badge <?php echo strtolower($order["status"]); ?>">
                                <?php echo $order["status"]; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-orders">Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>