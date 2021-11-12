<?php
include 'core/iccaFunctions2-new.php';
$icca_new_obj = new iccaFunc2New();
if(isset($_POST['key'])){
    $data = $icca_new_obj->fetcharticlesfromCategory($_POST['key']);
    $tr = "<tbody>";   $c=0;
        foreach ($data as $key => $value){ $c++;
                                        
            $tr .='<tr class="tblrow"><td>'.$c.'</td>';
            $tr .='<td>'.$value['article_id'].'</td>';
            $tr .='<td>'.$value['sentence_id'].'</td>';
            $tr .='<td>'.$value['sentence'].'</td>';
            $tr .='<td>'.$value['user_id'].'</td>';
            $tr .='<td>'.$value['status'].'</td>';
            $tr .='<td>'.$value['category_id'].'</td>';
            $tr .='<td>'.$value['id'].'</td>';
            $tr .='<td>'.$value['reviewed_by'].'</td>';
            $tr .='<td>'.$value['is_reject_rewrite'].'</td>';
            $tr .='<td>'.$value['created_at'].'</td>';
            $tr .='<td>'.$value['updated_at'].'</td>';
                                            
        }
        $tr .= "</tbody>";
        echo $tr;
}

?>