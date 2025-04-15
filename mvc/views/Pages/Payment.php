<!-- filepath: c:\xampp\htdocs\VNPay\mvc\views\Pages\Payment.php -->
<!DOCTYPE html>
<html lang="en">
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
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            color: #888;
            font-style: italic;
        }
        .status-success {
            color: green;
            font-weight: bold;
        }
        .status-error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kết quả thanh toán</h1>
        <table>
            <thead>
                <tr>
                    <th>Tham số</th>
                    <th>Giá trị</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Danh sách mã lỗi của VNPay
                $vnpResponseCodes = [
                    '00' => 'Giao dịch thành công',
                    '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
                    '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
                    '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần.',
                    '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
                    '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.',
                    '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
                    '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch.',
                    '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
                    '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
                    '75' => 'Ngân hàng thanh toán đang bảo trì.',
                    '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch.',
                    '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê).'
                ];

                // Duyệt qua tất cả các tham số trong $data
                foreach ($data as $key => $value) {
                    // Bỏ qua các tham số không cần thiết như vnp_SecureHash và secureHash
                    if (in_array($key, ['vnp_SecureHash', 'secureHash'])) {
                        continue;
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($key) . "</td>";

                    // Xử lý giá trị cho từng tham số
                    if ($key === 'vnp_Amount' && !is_null($value)) {
                        $formattedAmount = number_format($value / 100, 0, ',', '.') . " VND";
                        echo "<td>" . htmlspecialchars($formattedAmount) . "</td>";
                    } elseif ($key === 'vnp_ResponseCode' && !is_null($value)) {
                        // Xử lý mã lỗi và hiển thị thông điệp tương ứng
                        $responseMessage = $vnpResponseCodes[$value] ?? 'Mã lỗi không xác định';
                        $statusClass = ($value === '00') ? 'status-success' : 'status-error';
                        echo "<td>" . htmlspecialchars($value) . " <span class='$statusClass'>($responseMessage)</span></td>";
                    } else {
                        echo "<td>" . (is_null($value) ? "<span class='no-data'>Không có dữ liệu</span>" : htmlspecialchars($value)) . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>