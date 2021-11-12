<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    # fetch users for assignment Employee dropdown
    if($_GET['type'] == 'filter') {
        $employeeIds = $icca_new_obj->getAssignmentUsers(); 
        $result = '<option value="">All</option>';  
        
        foreach($employeeIds as $id) {
            $curr_user = $icca_new_obj->getUserById($id);
            $result .= "<option value='".$id."'>".$curr_user['fname'].' '.$curr_user['lname']."</option>";
        }
    } else {
        $employees = $icca_new_obj->getAllUsers(); 
        $result = '';  
        
        foreach($employees as $emp) {
            $curr_user = $icca_new_obj->getUserById($emp['id']);
            $result .= "<option value='".$emp['id']."'>".$curr_user['fname'].' '.$curr_user['lname']."</option>";
        }
    }
    echo $result;
?>