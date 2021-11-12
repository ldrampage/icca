<?php 

include("core/iccaFunctions.php");
$iccaFunc = new iccaFunc();

if(isset($_POST['formP'])){
   echo $iccaFunc->insertIntroPar($_POST,$_POST['article_id']);
}

if(isset($_POST['formS'])){
    $article_id = $_POST['article_id'];
    $subheading_title = $_POST['subheading_title'];
    //print_r($_POST);
    echo $iccaFunc->insertSubAndContent($_POST,$article_id,$subheading_title);
}

?>
