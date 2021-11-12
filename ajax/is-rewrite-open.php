<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_POST['id'])) {
        $result = $icca_new_obj->getRewrittenById($_POST['id']);
        echo json_encode([ 'is_open' => $result['is_open'], 'last_edit_time' => $result['last_edit_time'] ]);
    }
?>