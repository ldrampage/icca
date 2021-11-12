<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    date_default_timezone_set('Asia/Manila');
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    if(isset($_POST['id']) && isset($_POST['reviewer']) && isset($_POST['old_status'])) {
        # check if someone is editing the rewrite
        $rewrite = $icca_new_obj->getRewrittenById($_POST['id']);
        $now = new DateTime('NOW');
        $future = new DateTime($rewrite['last_edit_time']);
        $diffSeconds = $now->getTimestamp() - $future->getTimestamp();
        
        if(intval($rewrite['is_open']) || (!intval($rewrite['is_open']) && $diffSeconds >= 60)) {
            # update status
            if($_POST['reviewer']) {
                $result = $icca_new_obj->updateStatus($_POST['id'], 2, $_POST['reviewer'], $_POST['old_status']);
            } else {
                $result = $icca_new_obj->updateStatus($_POST['id'], 2, $_SESSION['login_id'], $_POST['old_status']);
            }
            
            # insert log
            $statuses = array("PENDING", "APPROVED", "REJECTED", "REJECTED & REWRITTEN");
            $user = $icca_new_obj->getUserById($_POST['reviewer']);
            $action = trim($user['fname'])." ".trim($user['lname'])." set the status of this rewrite";
            $from_ = $statuses[intval($_POST['old_status'])];
            $to_ = $statuses[2];
            $log = $icca_new_obj->addLog($_POST['id'], $action, $from_, $to_);
            
            echo json_encode($result);      
        } else {
            echo json_encode('false');
        }
    }
?>