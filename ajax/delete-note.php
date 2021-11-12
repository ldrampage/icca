<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_POST['id']) && isset($_POST['note']) && isset($_POST['uid']) && isset($_POST['rid'])) {
        # update status
        $result = $icca_new_obj->deleteNote($_POST['id']);
        
        # insert log
        $user = $icca_new_obj->getUserById($_POST['uid']);
        $action = trim($user['fname'])." ".trim($user['lname'])." deleted their note from this rewrite";
        $from_ = $_POST['note'];
        $log = $icca_new_obj->addLog($_POST['rid'], $action, $from_, '');
        
        echo json_encode($result);
    }
?>