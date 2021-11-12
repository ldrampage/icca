<?php 

include ('../core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
);
    
$table = 'tbl_new_articles';
$where = "status = 1 AND category_id = 0";
$primaryKey = 'id';

$columns = array(
    array(
        'db' => 'id',
        'dt' => 0,
        'formatter' => function($d,$row){
            return $d;
    }),
    
    array('db' => 'title', 'dt' => '1'),
    
    array(
        'db' => 'user_id', 
        'dt' => '2',
        'formatter' => function($d,$row) {
            return iccaFunc::fetchArticleCreator2($d);            
    }),
    
    array(
        'db' => 'created_at', 
        'dt' => '3',
        'formatter' => function($d, $row){
            return date('F j, Y h:i A', strtotime($d));
    }),
    
    array(
        'db' => 'ready_status', 
        'dt' => '4',
        'formatter' => function($d,$row){
            if($d == 0) {
                return "<span class='text-red text-bold'>NOT READY</span>";
            } else{
                return "<span class='text-green text-bold'>READY</span>";
            }
    }),
    
    array(
        'db' => 'ready_for_export', 
        'dt' => '5',
        'formatter' => function($d,$row){
            if($d == 0) {
                return "<span class='text-red text-bold'>NOT READY</span>";
            } else{
                return "<span class='text-green text-bold'>READY</span>";
            }
    }),
    
    array(
        'db' => 'id', 
        'dt' => '6',
        'formatter' => function($d,$row){
            return iccaFunc::articleNoCategoryListTbActions($d,$_GET['userLogin']);    
    }),
    
    array('db' => 'user_id', 'dt' => '7')
);

echo json_encode(SSP::complex($_GET, $dbDetails,$table,$primaryKey,$columns,$where));
?>