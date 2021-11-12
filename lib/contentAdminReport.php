<?php 

include ('../core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = "tbl_new_articles";
if($_GET['contentAdmin'] != ""){
   $where = "user_id='".$_GET['contentAdmin']."' AND status = 1"; 
} else{
    $where = "status = 1";
}

$primaryKey = 'id';
$columns = array(
    array(
        'db' => 'user_id', 
        'dt' => '0',
        'formatter' => function($d,$row){
            $user = iccaFunc::getUserById($d);
            return $user['fname']." ".$user['lname'];
        }),
    array('db' => 'title', 'dt' => '1'),
    array(
        'db' => 'updated_at', 
        'dt' => '2',
        'formatter' => function($d,$row){
            return date('F j, Y h:i A', strtotime($d));
        }),
    array(
        'db' => 'ready_status', 
        'dt' => '3',
        'formatter' => function($d,$row){
            if($d == 1) {
                return "<span style='color: green;'><strong>READY</strong></span>";
            }else{
                return "<span style='color: red;'><strong>NOT READY</strong></span>";
            }
        }),
    );
echo json_encode(
    SSP::complex($_GET, $dbDetails,$table,$primaryKey,$columns,$where)
);


?>