<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    # fetch rewrites status counts
    $result = $icca_new_obj->fetchRewriteStatusCounts();
    echo json_encode($result);
?>