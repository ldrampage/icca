<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    # fetch article export status counts
    $result = $icca_new_obj->fetchArticleExportStatusCounts();
    echo json_encode($result);
?>