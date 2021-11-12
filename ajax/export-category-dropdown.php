<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    # fetch article categories for Categories dropdown
    $categories = $icca_new_obj->getArticleCategories(' ORDER by name');
    $result = '<option value="">All</option>'; 
    
    foreach($categories as $category) {
        $selected = (isset($_GET['cid']) && $_GET['cid'] != '' && $_GET['cid'] == $category['id']) ? 'selected' : '';
        $result .= "<option value='".$category['id']."' ".$selected.">".$category['name']."</option>";   
    }
        
    echo $result;
?>