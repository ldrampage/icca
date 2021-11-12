<?php 

include ('core/ssp.class.php');

$dbDetails = array(
    'host' => DB_HOST2,
    'user' => DB_USER2,
    'pass' => DB_PASS2,
    'db' => DB_NAME2
    );
    
$query = " Select
      a.article_id, 
      a.sentence_id, 
      a.sentence, 
      a.user_id,
      a.status,
      b.category_id,
      a.id,
      a.reviewed_by,
      a.is_reject_rewrite,
      a.created_at,
      a.updated_at
      
    FROM tbl_rewrites a
    LEFT JOIN tbl_new_articles b ON a.article_id = b.id";
$primaryKey = 'id';
$table ="tbl_rewrites";
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
        'db' => 'article_id', 
        'dt' => 5,
        'formatter' => function($d,$row){
            // $cont_ = "WHERE id = $d";
            $f = iccaFunc2New::getArticleById($d);
            $f = $f['category_id'];
            // return $f;
            $categ = iccaFunc2New::fetchCategoryList($f);
            return $categ[0]['name'];
            // return $d;
        },
        'sel_cat'=>$f
        ),
    array(
        'db' => 'id',
        'dt' => 6,
        'formatter' => function($d,$row){
            return iccaFunc::rewrotedSentencesTbActions($d);   
        }),
    array(
        'db' => "reviewed_by",
        'dt' => 7,
        'formatter' => function($d,$row){
            return $d;  
        }),
    array(
        'db' => "is_reject_rewrite",
        'dt' => 8,
        'formatter' => function($d,$row){
            return $d;  
        }),
    array(
        'db' => 'created_at',
        'dt' => 9,
        'formatter' => function($d,$row){ //when the writer rewrote the sentence (created the rewrite)
            return $d;
        }),
    array(
        'db' => 'updated_at', //when the rewrite was approve/rejected/updated
        'dt' => 10,
        'formatter' => function($d,$row){
            return $d;
        })
    
    );


echo json_encode(
    SSP::simple2($_GET, $dbDetails,$table,$primaryKey,$columns,$searchColumnsBySubstring)
);


?>