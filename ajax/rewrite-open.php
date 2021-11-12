<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    date_default_timezone_set('Asia/Manila');
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_POST['id']) && isset($_POST['open'])) {
        # set 'open' to 0/1
        $result = $icca_new_obj->setRewriteOpen($_POST['id'], $_POST['open']);
        echo $result;
    }
?>