<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../lib/MTurk.class.php';
    $mturk_obj = new MTurkC();
    
    if(isset($_POST['hitId'])) {
        $selectedHIT = $mturk_obj->getHIT($_POST['hitId']);
        
        # If HIT is active then set it to expire immediately
        if($selectedHIT['HITStatus'] == 'Assignable') { 
            $mturk_obj->updateExpirationForHIT($_POST['hitId']);
        }
        
        # Delete the HIT
        $result = $mturk_obj->deleteHIT($_POST['hitId']);  
        echo json_encode($result);
    }
?>