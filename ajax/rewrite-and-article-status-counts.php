<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    # fetch rewrites status counts
    $r_result = $icca_new_obj->fetchRewriteStatusCounts();
    $a_result = $icca_new_obj->fetchArticleExportStatusCounts();
    
    $result = array(
        "allCount" => 0,
        "pending" => 0,
        "approved" => 0,
        "rejected" => 0,
        "perPending" => 0,
        "perApproved" => 0,
        "perRejected" => 0,
        "articlesCount" => 0,
        "articles_pending" => 0,
        "articles_completed" => 0,
    );
    
    $result['allCount'] = $r_result['allCount'];
    $result['pending'] = $r_result['pending'];
    $result['approved'] = $r_result['approved'];
    $result['rejected'] = $r_result['rejected'];
    $result['perPending'] = $r_result['perPending'];
    $result['perApproved'] = $r_result['perApproved'];
    $result['perRejected'] = $r_result['perRejected'];
    $result['articlesCount'] = $a_result['articlesCount'];
    $result['articles_pending'] = $a_result['articles_pending'];
    $result['articles_completed'] = $a_result['articles_completed'];
    
    echo json_encode($result);
?>