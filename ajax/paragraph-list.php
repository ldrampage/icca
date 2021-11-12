<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['id'])) {
        # fetch sentences
        $sentences = $icca_new_obj->getSentences('WHERE content_id = '.$_GET['id'].' ORDER BY paragraph_no, order_no');
        $no_of_paragraphs = 0;
        $curr_par = 0;
        $result = "<option value=''>- Select -</option>";
        foreach($sentences as $sentence) {
            if($curr_par !== $sentence['paragraph_no']) {
                $no_of_paragraphs++;
                $curr_par = $sentence['paragraph_no'];
                
                $result .= "<option value='".$curr_par."'>".$curr_par."</option>";
            }
        }
        echo $result;
    }
?>