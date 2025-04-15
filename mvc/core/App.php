<?php

class App
{
    protected $controller = "Home";
    protected $action = "Show";
    protected $params = [];

    function __construct()
    {
        $urlData = $this->UrlProcess();

        // Lấy các phần của URL (controller, action) và query params
        $urlParts = $urlData['urlParts'] ?? [];
        $queryParams = $urlData['queryParams'] ?? [];

        // Xử lý controller
        if (!empty($urlParts) && file_exists("./mvc/controllers/" . $urlParts[0] . ".php")) {
            $this->controller = $urlParts[0];
            unset($urlParts[0]);
        }
        require_once "./mvc/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;

        // Xử lý action
        if (isset($urlParts[1])) {
            if (method_exists($this->controller, $urlParts[1])) {
                $this->action = $urlParts[1];
            }
            unset($urlParts[1]);
        }

        // Gộp các phần còn lại của URL (nếu có) và query params vào $this->params
        $this->params = array_merge($urlParts ? array_values($urlParts) : [], $queryParams);

        // Gọi action với params (bao gồm cả query params)
        call_user_func_array([$this->controller, $this->action], [$this->params]);
    }

    function UrlProcess()
    {
        $urlParts = [];
        $queryParams = $_GET; // Lấy toàn bộ $_GET

        // Xử lý phần URL (controller/action)
        if (isset($_GET["url"])) {
            $urlParts = explode("/", filter_var(trim($_GET["url"], "/")));
            unset($queryParams["url"]); // Loại bỏ tham số "url" khỏi queryParams
        }

        // Trả về mảng chứa urlParts và queryParams
        return [
            'urlParts' => $urlParts,
            'queryParams' => $queryParams
        ];
    }
}