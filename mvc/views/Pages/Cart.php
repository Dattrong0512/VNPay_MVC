<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Pages\Cart.php -->
<h1 style="text-align: center; margin-bottom: 20px;">Giỏ hàng của bạn</h1>
<?php if (isset($data["Model"]) && is_array($data["Model"]) && count($data["Model"]) > 0): ?>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #f4f4f4; text-align: left;">
                <th style="padding: 10px; border: 1px solid #ddd;">Thứ tự</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Tên sản phẩm</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Giá</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Số lượng</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0; // Biến lưu tổng tiền
            foreach ($data["Model"] as $item):
                $total_price += $item["product_price"] * $item["product_amount"]; // Tính tổng tiền
            ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $item["orderdetail_id"]; ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $item["product_name"]; ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo number_format($item["product_price"], 0, ',', '.'); ?> VND</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $item["product_amount"]; ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <form action="/VNPay/Cart/DeleteItem" method="POST" style="display: inline;">
                            <input type="hidden" name="orderdetail_id" value="<?php echo $item['orderdetail_id']; ?>">
                            <button type="submit" style="padding: 5px 10px; background-color: #e74c3c; color: #fff; border: none; cursor: pointer; border-radius: 5px;">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Hiển thị tổng tiền -->
    <div style="text-align: right; margin-bottom: 20px; font-size: 18px;">
        <strong>Tổng tiền: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</strong>
    </div>

    <!-- Nút thanh toán -->
    <div style="text-align: center;">
        < action="/VNPay/Cart/Checkout" method="POST">
            <input type="hidden" name="sotien" value="<?= $total_price ?>" />
            <button type="submit" style="padding: 10px 20px; background-color: #2ecc71; color: #fff; border: none; cursor: pointer; border-radius: 5px; font-size: 16px;">
                Thanh toán
            </button>
            </form>
    </div>
<?php else: ?>
    <p style="text-align: center; color: #666;">Giỏ hàng của bạn đang trống.</p>
<?php endif; ?>