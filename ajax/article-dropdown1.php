<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['cid'])) {
        if($_GET['cid'] != '') {
            if(isset($_GET['export'])) {
                $articles = $icca_new_obj->getArticles(' WHERE category_id = '.$_GET['cid'].' AND ready_for_export = 0 AND ready_status = 1 AND status = 1 ORDER BY created_at DESC');
            } else {
                $articles = $icca_new_obj->getArticles(' WHERE category_id = '.$_GET['cid'].' AND status = 1 ORDER BY created_at DESC');    
            }
        } else {
            if(isset($_GET['export'])) {
                $articles = $icca_new_obj->getArticles(' WHERE ready_for_export = 0 AND ready_status = 1 AND status = 1 ORDER BY created_at DESC'); 
            } else {
                $articles = $icca_new_obj->getArticles(' WHERE status = 1 ORDER BY created_at DESC');    
            }
        }
        
        $start = '<option value="">All</option>'; 
        $result = '';
        
        foreach($articles as $article) {
            $result .= "<option value='".$article['id']."'>".$article['title']."</option>";  
        }
        
        if($result != '') {
            $result = $start . $result;
        } else {
           $result = '<option value="">No articles for the selected category</option>';  
        }
    } 
    echo $result;
?>