<?php
require_once("./mvc/core/Controller.php");

class Auth extends Controller
{
    public function __construct()
    {
        // Khởi tạo session nếu chưa có
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function Show($params = [])
    {
        // Lấy URL chuyển hướng nếu có
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/VNPay/Home';
        
        // Nếu đã đăng nhập, chuyển hướng
        if ($this->isLoggedIn()) {
            header("Location: $redirect");
            exit();
        }
        
        $this->view("Pages/Login", [
            "redirect" => $redirect
        ]);
    }
    
    public function Login()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /VNPay/Auth/Show");
            exit();
        }
        
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/VNPay/Home';
        
        // Gọi model để kiểm tra đăng nhập
        $authModel = $this->model("AuthModel");
        $user = $authModel->checkLogin($username, $password);
        
        if ($user) {
            // Đăng nhập thành công, lưu thông tin vào session
            $_SESSION['user_id'] = $user['customer_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['customer_name'] = !empty($user['customer_name']) ? $user['customer_name'] : $user['username'];
            $_SESSION['is_logged_in'] = true;
            
            // Chuyển hướng
            header("Location: $redirect");
            exit();
        } else {
            // Đăng nhập thất bại
            $this->view("Pages/Login", [
                "error" => "Tên đăng nhập hoặc mật khẩu không đúng",
                "redirect" => $redirect
            ]);
        }
    }
    
    public function Logout()
    {
        // Xóa các session liên quan đến đăng nhập
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['customer_name']);
        unset($_SESSION['is_logged_in']);
        
        // Hủy session
        session_destroy();
        
        // Chuyển về trang chủ
        header("Location: /VNPay/Home");
        exit();
    }
    
    public function Register()
    {
        // Hiển thị form đăng ký
        $this->view("Pages/Register", []);
    }

    public function ProcessRegister()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /VNPay/Auth/Register");
            exit();
        }

        $customer_name = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        // Kiểm tra mật khẩu nhập lại
        if ($password !== $confirm_password) {
            $this->view("Pages/Register", [
                "error" => "Mật khẩu nhập lại không khớp",
                "form_data" => [
                    "fullname" => $customer_name,
                    "username" => $username,
                    "email" => $email
                ]
            ]);
            return;
        }

        // Đăng ký người dùng
        $authModel = $this->model("AuthModel");
        $result = $authModel->registerUser($customer_name, $username, $password, $email);

        if ($result) {
            // Đăng ký thành công, chuyển đến trang đăng nhập
            $this->view("Pages/Login", [
                "success" => "Đăng ký thành công! Vui lòng đăng nhập."
            ]);
        } else {
            // Đăng ký thất bại
            $this->view("Pages/Register", [
                "error" => "Tên đăng nhập đã tồn tại",
                "form_data" => [
                    "fullname" => $customer_name,
                    "email" => $email
                ]
            ]);
        }
    }
    
    // Kiểm tra người dùng đã đăng nhập chưa
    public function isLoggedIn()
    {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
}