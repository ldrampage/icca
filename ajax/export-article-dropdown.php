<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['cid'])) {
        # fetch export-ready articles for Articles dropdown
        if($_GET['cid'] != '') {
            $articles = $icca_new_obj->getArticles(' WHERE status = 1 AND category_id = '.$_GET['cid'].' ORDER by title');    
        } else {
            $articles = $icca_new_obj->getArticles(' WHERE status = 1 ORDER by title');  
        }
        
        if(count($articles) > 0)
            $result = '<option value="">SELECT</option>'; 
        else
            $result = '<option value="">No articles for this category</option>'; 
        
        foreach($articles as $article) {
            //check if article is avail for export
            // $avail_result = $icca_new_obj->checkExportAvailability($article['id']);
            if(intval($article['ready_for_export'])) { //$avail_result['value']
                $selected = (isset($_GET['aid']) && $_GET['aid'] != '' && $_GET['aid'] == $article['id']) ? 'selected' : '';
                $result .= "<option value='".$article['id']."' ".$selected.">".$article['title']."</option>";   
            }
        }
    } 
    echo $result;
?>