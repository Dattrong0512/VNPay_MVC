<?php


require_once("./mvc/core/Controller.php");
class Payment extends Controller
{

  function Show()
  {
    $view = $this->view("Layout/MainLayout",["Page" => "Pages/Payment"]);
  }

}
