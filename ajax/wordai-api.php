<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    echo $icca_new_obj->processSpin($_POST);
?>