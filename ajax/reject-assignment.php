<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../lib/MTurk.class.php';
    $mturk_obj = new MTurkC();
    
    if(isset($_POST['assignId']) && isset($_POST['feedback'])) {
        # Setting up params
        $params = array(
            'AssignmentId' => $_POST['assignId'],
            'RequesterFeedback' => $_POST['feedback'], 
        );
        
        # Reject the assignment
        $result = $mturk_obj->rejectAssignment($params);  
        echo json_encode($result);
    }
?>