<?php 

include ('core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$table = 'tbl_rewrites';
$primaryKey = 'id';

$searchColumnsBySubstring = array(8,9); //created_at, updated_at

$columns = array(
    array(
        'db' => 'article_id', 
        'dt' => 0,
        'formatter' => function($d,$row){
            return iccaFunc::fetchArticleTitleForRewroteSentence($d);
        }),
    array(
        'db' => 'sentence_id', 
        'dt' => 1,
        'formatter' => function($d,$row) {
            $res = iccaFunc::htmlallentities(trim(iccaFunc::fetchSentenceForRewrotedSentence($d)));
            return $res;
        }),
    array(
        'db' => 'sentence', 
        'dt' => 2,
        'formatter' => function($d, $row){
            return iccaFunc::htmlallentities(trim(($d)));
        }),
    array(
        'db' => 'user_id',
        'dt' => 3,
        'formatter' => function($d,$row) {
            $user = iccaFunc::getUserById($d);
            if(!empty($user)){
               return $user['fname']." ".$user['lname']; 
            } else{
                return "Mturk Worker ID: " . $d;
            }
            
        }),
    array(
        'db' => 'status', 
        'dt' => 4,
        'formatter' => function($d,$row) {
            return iccaFunc::rewrotedSentenceStatus($d);
        }),
    array(
        'db' => 'id',
        'dt' => 5,
        'formatter' => function($d,$row){
            return iccaFunc::rewrotedSentencesTbActions($d);   
        }),
    array(
        'db' => "reviewed_by",
        'dt' => 6,
        'formatter' => function($d,$row){
            return $d;  
        }),
    array(
        'db' => "is_reject_rewrite",
        'dt' => 7,
        'formatter' => function($d,$row){
            return $d;  
        }),
    array(
        'db' => 'created_at',
        'dt' => 8,
        'formatter' => function($d,$row){ //when the writer rewrote the sentence (created the rewrite)
            return $d;
        }),
    array(
        'db' => 'updated_at', //when the rewrite was approve/rejected/updated
        'dt' => 9,
        'formatter' => function($d,$row){
            return $d;
        }),
    array(
        'db' => 'category_id', //when the rewrite was approve/rejected/updated
        'dt' => 10,
        'formatter' => function($d,$row){
            return $d;
        })
    );


echo json_encode(
    SSP::simple($_GET, $dbDetails,$table,$primaryKey,$columns,$searchColumnsBySubstring)
);


?>