<?php

use Dom\Mysql;

class DB
{
    public $conn;
    public $servername = "157.230.47.29";
    public $username = "root";
    public $password = "12345678Aa";
    public $dbname = "VNPAYSHOP";

    function __construct()
    {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password);
        mysqli_select_db($this->conn, $this->dbname);
        mysqli_query($this->conn, "SET NAMES 'utf8mb4'");
    }
}
