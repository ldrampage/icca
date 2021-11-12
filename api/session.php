<?php


if (session_status() == PHP_SESSION_NONE) {
session_start();
}

if(isset($_SESSION['login'])==true){
	echo json_encode(array('id'=>$_SESSION['id'], 'login'=>true));
}else{
	echo json_encode(array('id'=>null, 'login'=>false));
}



?>