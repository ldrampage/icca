<?php 

include ('../core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
$table = "tbl_category";

$primaryKey = 'id';
$columns = array(
    
    array('db' => 'id', 'dt' => '0'),
    array('db' => 'name', 'dt' => '1'),
    array(
        'db' => 'created_by', 
        'dt' => '2',
        'formatter' => function($d,$row){
            $user = iccaFunc::getUserById($d);
            return $user['fname']." ".$user['lname'];
        }),
    array(
        'db' => 'created_at', 
        'dt' => '3',
        'formatter' => function($d,$row){
            return date('F j, Y h:i A', strtotime($d));
        }),
    array(
        'db' => 'id', 
        'dt' => '4',
        'formatter' => function($d,$row){
            $cat = iccaFunc::fetchCategoryName($d);
            $name = $cat['name'];
            return "<center><button class='btn btn-warning btn-xs' data-toggle='modal' onclick='editCategory($d,\"$name\")'><i class='fa fa-magic'></i>&nbsp;Edit</button>
                    &nbsp;<button class='btn btn-success btn-xs' data-toggle='modal' onclick='applyCategory($d,\"$name\")'><i class='fa fa-hand-pointer'></i>&nbsp;Apply</center>";
        }),
    );
    
echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns)
);


?>