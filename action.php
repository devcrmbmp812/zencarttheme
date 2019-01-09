<?php 
 include('config.php');  
if(isset($_POST['submit']))
  {
    $theme = $_POST['template_dir'];     
    $cookie_name = "user";
    $cookie_value = "crish";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: /index.php?main_page=index&cPath=2");
  }

if(isset($_POST['submit_helloween']))
  { 
    $cookie_name = "user";
    $cookie_value = "hello";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: /index.php?main_page=index&cPath=34");
  }
 
if(isset($_POST['submit_themes']))
  {
    $cookie_name = "user";
    $cookie_value = "othemes";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: /index.php?main_page=index&cPath=36");
        
  }


if(isset($_POST['submit_projects']))
  {
    $cookie_name = "user";
    $cookie_value = "other";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: /index.php?main_page=index&cPath=20");
       
  }


if(isset($_GET['ac']))
  {
    $cookie_name = "user";
    $cookie_value = "";
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: index.php");
       
  }

?> 
