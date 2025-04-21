<?php

use Dom\Mysql;

class DB
{
    public $conn;
    public $servername = "localhost";
    public $username = "minhquan";
    public $password = "12345";
    public $dbname = "VNPAYSHOP";

    function __construct()
    {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password);
        mysqli_select_db($this->conn, $this->dbname);
        mysqli_query($this->conn, "SET NAMES 'utf8mb4'");
    }
}
