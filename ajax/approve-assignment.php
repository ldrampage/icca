<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../lib/MTurk.class.php';
    $mturk_obj = new MTurkC();
    
    if(isset($_POST['assignId']) && isset($_POST['feedback'])) {
        # Get assignment
        $selectedAssign = $mturk_obj->getAssignment($_POST['assignId']);
        
        $params = array(
            'AssignmentId' => $_POST['assignId'],
            'RequesterFeedback' => $_POST['feedback'], 
        );
        
        # Overried Rejection status if assignment is previously rejected
        if($selectedAssign["AssignmentStatus"] == 'Rejected') {
            $params['OverrideRejection'] = true;
        }
        
        # Reject the assignment
        $result = $mturk_obj->approveAssignment($params);  
        echo json_encode($result);
    }
?>