<?php 

include ('core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = 'tbl_new_articles';
$where = "status='1' AND ready_status='1' AND ready_for_export='0' ORDER BY rewrite_progress DESC";
$primaryKey = 'id';

$columns = array(
    array('db' => 'id', 'dt' => 'num'),
    array('db' => 'title', 'dt' => 'title'),
    array(
        'db' => 'user_id', 
        'dt' => 'creator',
        'formatter' => function($d,$row) {
            $user = iccaFunc::getUserById($d);
            return $user['fname']." ".$user['lname'];
        }),
    array(
        'db' => 'id',
        'dt' => 'rewrite_stats',
        'formatter' => function($d,$row){
            $rs = iccaFunc2New::getRewriteProgress($d);
            if($rs >= 80){
                return $rs . "%" . "<a class='btn btn-danger btn-xs pull-right' href='?page=priority&articleId=$d' target='_blank'>View remaining rewrite</a>";
                //return $rs . "%";
            } else {
                return $rs . "%";
            }
           
        }),
    array(
        'db' => 'created_at', 
        'dt' => 'date_created',
        'formatter' => function($d,$row){ 
            return date('F j, Y h:i A', strtotime($d));
        }),
    
    
    array(
        'db' => 'category_id', 
        'dt' => 'category_id',
        'formatter' => function($d,$row){ 
            $categ = iccaFunc2New::fetchCategoryList($d);
            return $categ[0]['name'];
        }),
   
    array(
        'db' => 'id',
        'dt' => 'action',
        'formatter' => function($d,$row) {
            return iccaFunc::readyForRewriteTbActions($d);
        })
    );


echo json_encode(
    SSP::complex($_GET, $dbDetails,$table,$primaryKey,$columns,$where)
    );


?>