<?php

   //require_once('core.php');
   session_start();
   
   
   
   if(!isset($_SESSION['login_user'])){
   	  $current_url = $_SERVER['REQUEST_URI'];
     if (strpos($current_url, '?page=login') === false) {
    	header("location: index.php?page=login");
	 }
     // header("location: index.php?page=login");

   }




?>