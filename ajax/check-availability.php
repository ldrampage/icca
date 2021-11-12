<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['aid'])) {
        # fetch rewrites
        $result = $icca_new_obj->checkExportAvailability($_GET['aid']);
        echo json_encode($result);
    }
?>