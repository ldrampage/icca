<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['cid'])) {
        $articleIds = $icca_new_obj->getRAArticleIds();
        $start = '<option value="">All</option>'; 
        $result = '';
        
        foreach($articleIds as $aid) {
            $article = $icca_new_obj->getArticleById($aid);
            if(($_GET['cid'] != '' && $article['category_id'] == $_GET['cid']) || $_GET['cid'] == '') {
                $result .= "<option value='".$article['id']."'>".$article['title']."</option>";            
            }
        }
        
        if($result != '') {
            $result = $start . $result;
        } else {
           $result = '<option value="">No articles for the selected category</option>';  
        }
    } 
    echo $result;
?>