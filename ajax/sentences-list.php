<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_GET['content_id']) && isset($_GET['par_id'])) {
        # fetch sentences
        $sentences = $icca_new_obj->getSentences('WHERE content_id = '.$_GET['content_id'].' AND paragraph_no = '.$_GET['par_id'].' ORDER BY order_no');
        $index = 1;
        $result = '';
        foreach($sentences as $sentence) {
            $result .= "<option value='".$index++."'>".$icca_new_obj->htmlallentities(trim($sentence['sentence']))."</option>";
        }
        echo $result;
    }
?>