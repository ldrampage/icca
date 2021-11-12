<?php 

include ('../core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = "tbl_rewrites";

$dT = explode("/",$_GET['dateTo']);
$dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";

$where = "user_id='".$_GET['writer']."' AND (updated_at >= '".$_GET['dateFrom']."' AND updated_at <= '".$_GET['dateTo']."') AND (status='2' OR status='3')";
$primaryKey = 'id';
$columns = array(
    
    array(
        'db' => 'updated_at', 
        'dt' => '0',
        'formatter' => function($d,$row){
             return date('F j, Y h:i A', strtotime($d));
        }),
    array(
        'db' => 'reviewed_by', 
        'dt' => '1',
        'formatter' => function($d,$row){
            $user = iccaFunc::getUserById($d);
            return $user['fname']." ".$user['lname'];
        }),
    array(
        'db' => 'id', 
        'dt' => '2',
        'formatter' => function($d,$row){
            return "<a href='?page=rewritten-sentence&id=$d' class='btn btn-primary btn-xs' target='_blank'>View</a>";
        }),
    );
echo json_encode(
    SSP::complex($_GET, $dbDetails,$table,$primaryKey,$columns,$where)
);


?>