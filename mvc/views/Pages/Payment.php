<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 30px;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: black;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-success {
            color: green;
            font-weight: 600;
        }
        .status-error {
            color: red;
            font-weight: 600;
        }
        .actions {
            text-align: center;
            margin-top: 30px;
        }
        .shop-button {
            background-color: #222;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .shop-button:hover {
            background-color: #444;
        }
        .payment-status {
            text-align: center;
            font-size: 24px;
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .payment-success {
            background-color: #e6f7e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kết quả thanh toán</h1>
        
        <?php if (isset($data['vnp_ResponseCode']) && $data['vnp_ResponseCode'] === '00'): ?>
        <div class="payment-status payment-success">
            <i class="fa fa-check-circle"></i> Thanh toán thành công
        </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Thông tin</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $displayFields = [
                    'vnp_Amount' => 'Số tiền',
                    'vnp_BankCode' => 'Ngân hàng',
                    'vnp_OrderInfo' => 'Nội dung thanh toán',
                    'vnp_PayDate' => 'Thời gian thanh toán',
                    'vnp_ResponseCode' => 'Trạng thái',
                ];
                
                foreach ($displayFields as $key => $label) {
                    if (isset($data[$key])) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($label) . "</td>";
                        if ($key === 'vnp_Amount') {
                            $formattedAmount = number_format($data[$key] / 100, 0, ',', '.') . " VND";
                            echo "<td>" . htmlspecialchars($formattedAmount) . "</td>";
                        } 
                        elseif ($key === 'vnp_PayDate') {
                            $dateString = $data[$key];
                            $year = substr($dateString, 0, 4);
                            $month = substr($dateString, 4, 2);
                            $day = substr($dateString, 6, 2);
                            $hour = substr($dateString, 8, 2);
                            $minute = substr($dateString, 10, 2);
                            $second = substr($dateString, 12, 2);
                            
                            $formattedDate = "$day/$month/$year $hour:$minute:$second";
                            echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                        }
                        elseif ($key === 'vnp_ResponseCode') {
                            if ($data[$key] === '00') {
                                echo "<td><span class='status-success'>Giao dịch thành công</span></td>";
                            } else {
                                echo "<td><span class='status-error'>Giao dịch thất bại</span></td>";
                            }
                        }
                        else {
                            echo "<td>" . htmlspecialchars($data[$key]) . "</td>";
                        }
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="actions">
            <button class="shop-button" onclick="window.location.href='/VNPay/Home'">
                Tiếp tục mua sắm
            </button>
        </div>
    </div>
</body>
</html>