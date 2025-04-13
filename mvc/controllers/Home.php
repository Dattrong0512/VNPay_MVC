<?php

// http://localhost/live/Home/Show/1/2

require_once("./mvc/core/Controller.php");
class Home extends Controller
{

  // Must have SayHi()
  function SayHi()
  {
    echo "Dit me may";
  }
  function Show()
  {
    $this->view("Home");
  }
}
