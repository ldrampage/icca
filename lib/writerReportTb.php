<?php 

include ('../core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = 'tbl_assignments';
if($_GET['writer'] == ""){
    $where = "role_id=5 OR role_id=6"; 
} else{
    $where = "(role_id='5' OR role_id='6') AND user_id='".$_GET['writer']."'";
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
    array(
        'db' => 'user_id', 
        'dt' => '1',
        'formatter' => function($d,$row){
            // return approved rewrites
            $approvedRewrites = iccaFunc::writersApproveRewrites($d,$_GET['dateFrom'],$_GET['dateTo']);
            return "<span class='text-success'><strong>".$approvedRewrites."</strong></span>";
        }),
    array(
        'db' => 'user_id', 
        'dt' => '2',
        'formatter' => function($d, $row){
            // return pending rewrites
            $pendingRewrites = iccaFunc::writersPendingRewrites($d, $_GET['dateFrom'],$_GET['dateTo']);
            return "<span class='text-info'><strong>".$pendingRewrites."</strong></span>";
            
        }),
    array(
        'db' => 'user_id', 
        'dt' => '3',
        'formatter' => function($d,$row){
            // return pending rejected
            $rejectedRewrites = iccaFunc::writersRejectedRewrites($d, $_GET['dateFrom'],$_GET['dateTo']);
            if($rejectedRewrites != 0){
                return "<span class='text-danger'><strong>".$rejectedRewrites."</strong></span><button class='btn btn-danger btn-xs pull-right' value ='$d' data-toggle='modal' data-target='#rejectedDetails' onclick='rejected(this.value)'>Details</button>";
            } else{
                return "<span class='text-danger'><strong>".$rejectedRewrites."</strong></span>";
            }
        }),
    );
echo json_encode(
    SSP::complex($_GET, $dbDetails,$table,$primaryKey,$columns,$where)
);


?>