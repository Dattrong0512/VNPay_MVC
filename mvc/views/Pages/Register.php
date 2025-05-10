<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Gym Shop</title>
    <link rel = "stylesheet" href = "/VNPay/public/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        
        body, html {
            height: 100%;
            width: 100%;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Màu nền mờ */
            backdrop-filter: blur(5px); /* Hiệu ứng mờ */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .register-container {
            background-color: #fff;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            animation: fadeIn 0.5s;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .register-header h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .register-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: #aaa;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #222;
            outline: none;
        }
        
        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: #222;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-register:hover {
            background-color: #444;
        }
        
        .form-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        
        .form-footer a {
            color: #222;
            text-decoration: none;
            font-weight: 500;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="register-container">
            <div class="register-header">
                <h2>Đăng ký tài khoản</h2>
                <p>Đăng ký để trải nghiệm mua sắm tốt hơn</p>
            </div>
            
            <?php if (isset($data['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>
            
            <form action="/VNPay/Auth/ProcessRegister" method="POST">
                <div class="form-group">
                    <label for="fullname">Họ và tên</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" id="fullname" name="fullname" required
                            value="<?php echo isset($data['form_data']['fullname']) ? $data['form_data']['fullname'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <div class="input-group">
                        <i class="fas fa-user-tag"></i>
                        <input type="text" class="form-control" id="username" name="username" required
                            value="<?php echo isset($data['form_data']['username']) ? $data['form_data']['username'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control" id="email" name="email" required
                            value="<?php echo isset($data['form_data']['email']) ? $data['form_data']['email'] : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-register">Đăng ký</button>
            </form>
            
            <div class="form-footer">
                Đã có tài khoản? <a href="/VNPay/Auth/Show">Đăng nhập</a>
            </div>
        </div>
    </div>

    <script>
        // Nếu người dùng nhấp vào nơi khác trên trang, quay lại trang chủ
        document.querySelector('.overlay').addEventListener('click', function(event) {
            if (event.target === this) {
                window.location.href = '/VNPay/Home';
            }
        });

        // Kiểm tra mật khẩu khớp nhau
        document.querySelector('form').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Mật khẩu nhập lại không khớp');
            }
        });
    </script>
</body>
</html>