<?php 

include ('core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = 'tbl_assignments';
$primaryKey = 'id';

$columns = array(
        array('db' => 'id', 'dt' => '0'),
        array(
            'db' => 'user_id', 
            'dt' => '1',
            'formatter' => function($d,$row){
                $user = iccaFunc2New::getUserById($d);
                return $user['fname']." ".$user['lname'];
            }),
        array(
            'db' => 'user_id', 
            'dt' => '2',
            'formatter' => function($d,$row){
                $user = iccaFunc::getUserById($d);
                $dept = iccaFunc2New::getDeptById($user['department_id']);
                return $dept['name'];
            }),
        array(
            'db' => 'role_id', 
            'dt' => '3',
            'formatter' => function($d,$row){
                $role = iccaFunc2New::getRoleById($d);
                return $role['role'];
            }),
        array(
            'db' => 'id', 
            'dt' => '4',
            'formatter' => function($d,$row){
                $aA = iccaFunc::fetchUserByAssignId($d);
                
                $user = iccaFunc::getUserById($aA['user_id']);
                $fullName = $user['fname']." ".$user['lname'];
                $dept = iccaFunc2New::getDeptById($user['department_id']);
                $id = $aA['id'];
                $user_id = $aA['user_id'];
                $role_id = $aA['role_id'];
                $deptName = $dept['name'];
                
                $role = iccaFunc2New::getRoleById($role_id);
                $r = $role['role'];
                $action = "";
                $action .= "<center><a href='javascript:showEditModal(".$aA['id'].",\"$fullName\",\"$deptName\",$user_id,$role_id)'><button  class='btn btn-warning btn-xs mtop-6'><i class='fa fa-pencil-alt'></i>&nbsp;&nbsp;Edit</button></a>";
                $action .= " <a href='javascript:confirmDelete(".$aA['id'].",\"$fullName\", \"$r\")'><button class='btn btn-danger btn-xs mtop-6'><i class='fa fa-times'></i>&nbsp;&nbsp;Delete</button></a></center>";
                
                return $action;
                
            }),
    );


echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns)
);


?>