<?php 

include ('../core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = 'tbl_mturk_workers';
$primaryKey = 'id';
$columns = array(
    array('db' => 'worker_id', 'dt' => '0'),
    array(
        'db' => 'worker_id', 
        'dt' => '1',
        'formatter' => function($d,$row){
            $approvedRewrites = iccaFunc::mturkWritersApprovedRewrites($d,$_GET['dateFrom'],$_GET['dateTo']);
            return "<span class='text-success'><strong>".$approvedRewrites."</strong></span>";
        }),
    array(
        'db' => 'worker_id', 
        'dt' => '2',
        'formatter' => function($d,$row){
            $pendingRewrites = iccaFunc::mturkWritersPendingRewrites($d, $_GET['dateFrom'],$_GET['dateTo']);
            return "<span class='text-info'><strong>".$pendingRewrites."</strong></span>";
        }),
    array(
        'db' => 'worker_id', 
        'dt' => '3',
        'formatter' => function($d,$row){
            // return pending rejected
            $rejectedRewrites = iccaFunc::mturkWritersRejectedRewrites($d, $_GET['dateFrom'],$_GET['dateTo']);
            if($rejectedRewrites != 0){
                return "<span class='text-danger'><strong>".$rejectedRewrites."</strong></span><button class='btn btn-danger btn-xs pull-right' value ='$d' data-toggle='modal' data-target='#rejectedDetails' onclick='rejected(this.value)'>Details</button>";
            } else{
                return "<span class='text-danger'><strong>".$rejectedRewrites."</strong></span>";
            }
        }),
    );
echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns)
);


?>