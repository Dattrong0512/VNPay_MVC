<h1 class="page-title">Giỏ hàng của bạn</h1>
<?php if (isset($data["Model"]) && is_array($data["Model"]) && count($data["Model"]) > 0): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0; // Biến lưu tổng tiền
            foreach ($data["Model"] as $item):
                $total_price += $item["product_price"] * $item["product_amount"]; // Tính tổng tiền
            ?>
                <tr>
                    <td><?php echo $item["product_id"]; ?></td>
                    <td><?php echo $item["product_name"]; ?></td>
                    <td><?php echo number_format($item["product_price"], 0, ',', '.'); ?> VND</td>
                    <td><?php echo $item["product_amount"]; ?></td>
                    <td>
                        <form action="/VNPay/Cart/DeleteItem" method="POST" class="delete-form" onsubmit="updateCartCount(-1); return true;">
                            <input type="hidden" name="orderdetail_id" value="<?php echo $item['orderdetail_id']; ?>">
                            <button type="submit" class="btn btn-danger">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Hiển thị tổng tiền -->
    <div class="cart-total">
        <strong>Tổng tiền: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</strong>
    </div>

    <!-- Nút thanh toán -->
    <div class="cart-actions">
        <form action="/VNPay/Cart/Confirm_VNPay" method="POST">
            <input type="hidden" name="sotien" value="<?= $total_price ?>" />
            <input type="hidden" name="orders_id" value="<?= $data['Model'][0]['orders_id'] ?>" />
            <button type="submit" class="btn btn-primary checkout-button">
                Thanh toán
            </button>
        </form>
    </div>
<?php else: ?>
    <p class="empty-cart">Giỏ hàng của bạn đang trống.</p>
<?php endif; ?>