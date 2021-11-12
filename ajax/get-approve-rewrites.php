<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['sql'])) {
        # fetch rewrites
        $result = count($icca_new_obj->getRewrittenArticles($_GET['sql']));
        
        echo $result;
    }
?>