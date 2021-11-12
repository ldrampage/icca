<?php 
    define("DB_HOST2", "localhost");
    define("DB_USER2", "acllcmas_icca_user");
    define("DB_PASS2", "v@4K548#M{/;X[$<");
    define("DB_NAME2", "acllcmas_icca");
    
    
    define("DB_HOST3", "68.66.214.205");
    define("DB_USER3", "backoff3_tmuhr");
    define("DB_PASS3", "sTmwbw4a0Ylp");
    define("DB_NAME3", "backoff3_mhr"); 
    /*
    define("DB_HOST3", "localhost");
    define("DB_USER3", "acllcmas_nmuhr");
    define("DB_PASS3", "_kc?~Ew[%5,z");
    define("DB_NAME3", "acllcmas_mhr"); */
    
    date_default_timezone_set('Asia/Manila');
    
    class iccaFunc {
        
        function getMyUsers($id=null){
            $c="";
            if($id!=null) { $c = " WHERE id = '$id'"; }
            $dataq = array(
                'model'=>'employee',
                'order'=>" ORDER BY fname",
                'condition'=>$c
            );
            
            $u = $this->getRecord2($dataq);
            return $u['data'];
        }
        
         function connectLogin($data=null){
            return mysqli_connect(DB_HOST3, DB_USER3, DB_PASS3, DB_NAME3);
        }
        
        function getRecord2($data){
            if(!self::connectLogin()) {
                return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
            } else {
                if (array_key_exists("condition",$data)){ $condition = $data['condition']; }else{ $condition = ""; }
                if (array_key_exists("order",$data)){ $order = $data['order']; }else{ $order = ""; }
                $model = 'tbl_'.$data['model'];
                $sql = "SELECT * FROM $model ".$condition." ".$order;
                $result = mysqli_query(self::connectLogin(),$sql) or die(mysqli_connect_error());
                $total = mysqli_num_rows($result);
                $index = 0;
                $responseData=array();
                while($fetchData = mysqli_fetch_assoc($result)){
                    if (array_key_exists("secpass",$fetchData)){
                        $fetchData['secpass'] = '';
                    }
                    $responseData[$index] = $fetchData;
                    $index++;
                }
                if (array_key_exists("method",$data)) {
                    if($data['method']=="login") { return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData); }
                    else { mysqli_close(self::connectLogin()); return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData); }
                } else {
                    mysqli_close(self::connectLogin());
                    return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);
                }
            }
        }
        
        function getAllUsers() {
            $conn = new mysqli(DB_HOST3,DB_USER3,DB_PASS3,DB_NAME3);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_employee WHERE status = 1 ORDER BY fname";
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
                    $aList = array();
                    while($row = $result->fetch_assoc()){
                        $aList[$row['id']] = $row['fname']." ".$row['lname'];
                    }
                    $conn->close();
                    return $aList;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        // FUNCTIONS FOR ARTICLE CATEGORY
        
        function fetchAllCategory(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_category ORDER BY name ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                } else{
                    $conn->close();
                    return array();
                }
                
            }
        }
        
        function applyCategoryToArticle($articles,$data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $flag = true;
                $user_id = $data['user_id'];
                $cat_id = $data['cat_id'];
                $updated_at = date("Y-m-d H:i:s");
                
                foreach($articles as $article_id){
                    
                    $sql = "UPDATE tbl_new_articles SET category_id='$cat_id' WHERE id='$article_id'";
                    if($conn->query($sql) === TRUE){
                        // edit action to logs
                        $user = $this->getUserById($user_id);
                        $action = $user['fname']." ".$user['lname']." apply category to article_id = $article_id";
                        $sql = "INSERT INTO tbl_logs (created_at, action) VALUES('$updated_at','$action')";
                        $conn->query($sql);
                        // edit action to logs
                        
                    } else{
                        $flag = false;
                    }
                    
                }
                if($flag){
                    $conn->close();
                    return "apply-success";
                }
                $conn->close();
                return "failed";
                
                
            }
            
            
        }
    
        function editArticleCategory($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($this->articleCategoryNameExist($data['name'])){
                    $conn->close();
                    return "category_name_exist";  
                } else{
                    $name = mysqli_real_escape_string($conn,strtolower($data['name']));
                    $id = mysqli_real_escape_string($conn,$data['cat_id']);
                    $editor = mysqli_real_escape_string($conn,$data['user_id']);
                    $from_ = mysqli_real_escape_string($conn,$data['from_']);
                    $updated_at = date("Y-m-d H:i:s");
                    
                    $sql = "UPDATE tbl_category SET name='$name', updated_at='$updated_at' WHERE id='$id'";
                    if($conn->query($sql) === TRUE){
                        // edit action to logs
                        $user = $this->getUserById($editor);
                        $action = $user['fname']." ".$user['lname']." edited category name with id = $id";
                        $sql = "INSERT INTO tbl_logs (created_at, action, from_, to_) VALUES('$updated_at','$action','$from_','$name')";
                        $conn->query($sql);
                        // edit action to logs
                        
                        $conn->close();
                        return "success-edit";
                    } else{
                        return "failed";
                    }
                }
            }
        }
        
        function insertArticleCategory($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($this->articleCategoryNameExist($data['name'])){
                    $conn->close();
                    return "category_name_exist";  
                } else{
                    $name = mysqli_real_escape_string($conn,strtolower($data['name']));
                    $created_by = mysqli_real_escape_string($conn,$data['user_id']);
                    $created_at = date("Y-m-d H:i:s");
                    $updated_at = date("Y-m-d H:i:s");
                    
                    $sql = "INSERT INTO tbl_category (name,created_by,created_at,updated_at) VALUES ('$name','$created_by','$created_at','$updated_at')";
                    if($conn->query($sql) === TRUE){
                        
                        // insert action to logs
                        $last_id = $conn->insert_id;
                        $user = $this->getUserById($created_by);
                        $action = $user['fname']." ".$user['lname']." created a new category with id = $last_id";
                        $sql = "INSERT INTO tbl_logs (created_at, action) VALUES ('$created_at','$action')";
                        $conn->query($sql);
                        // insert action to logs
                        
                        $conn->close();
                        return "success";
                    } else{
                        $conn->close();
                        return "failed";
                    }
                }
            }
        }
        
        function articleCategoryNameExist($name){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $name = mysqli_real_escape_string($conn,strtolower($name));
                $sql = "SELECT * FROM tbl_category WHERE name='$name'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function fetchCategoryName($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_category WHERE id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $conn->close();
                    return $row;
                } else{
                    $conn->close();
                    return array();
                }
                
            }
        }
        
        // FUNCTIONS FOR ARTICLE CATEGORY
        
        
        // FUNCTION FOR SSP ROLE LIST TABLE
        
        function fetchUserByAssignId($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    return $row;
                }
            }
        }
        
        // FUNCTION FOR SSP ROLE LIST TABLE
        
        // FUNCTIONS FOR SSP ARTICLE LIST TABLE
        
        function fetchArticleCreator2($user_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $user = iccaFunc::getUserById($user_id);
                return $user['fname']." ".$user['lname'];
            }
        }
        
        function articleListTbActions($article_id,$login_id){
            $actions = "";
            
            $actions .= "<center><a href='?page=article-view&aid=$article_id' class='btn btn-primary btn-xs' target='_blank'><i class='fa fa-eye'></i>&nbsp;View</a>";
            $actions .= " <a href='?page=createArticleNew&articleId=$article_id' class='btn btn-info btn-xs' target='blank'><i class='fa fa-plus'></i>&nbsp;Add</a>";
            $actions .= " <a href='?page=editArticle&articleId=$article_id' class='btn btn-xs btn-success' target='blank'><i class='fa fa-magic'></i>&nbsp;Edit</a>";
            $actions .= " <button class='btn btn-xs btn-danger' value='$article_id' onclick='deleteArt(this.value)'><i class='fa fa-trash'></i>&nbsp;Delete</button>";
            
             
            $articleReady = iccaFunc::isReadyArticle($article_id);
            $userRole = iccaFunc::fetchRoleId($login_id);
            if($articleReady){
                $disable = "";
            } else{ $disable = 'disabled'; }
            
            $article = iccaFunc::fetchArticleByid($article_id);
            
            if($login_id == $article[0]['user_id'] || $userRole[0]['role_id'] == 1){
                
                if($article[0]['ready_status'] == "0"){
                    $actions .= " <button class='btn btn-xs btn-warning' onclick='ready($article_id)' $disable><i class='fa fa-check-square'></i>&nbsp;Ready</button></center>";
                } else{
                    $actions .= " <button class='btn btn-xs btn-primary' onclick='saveAsDraft($article_id)'><i class='fa fa-edit'></i>&nbsp;Save as Draft</button></center>";
                }
                
            } else{
                $actions .= "</center>";
            }
            
            return $actions;
            
        }
        // FUNCTIONS FOR SSP ARTICLE LIST TABLE
        
        // FUNCTIONS FOR PERFORMANCE REPORT
        function articleNoCategoryListTbActions($article_id,$login_id) {
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            $actions = "";
            
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_new_articles WHERE id = '$article_id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $res = $result->fetch_assoc();
                    $articleTitle = htmlspecialchars(json_encode(iccaFunc::convert_smart_quotes(iccaFunc::htmlallentities($res['title']))));
                    
                    $actions .= '<center>
                        <a href="javascript:showCategoryModal('.$article_id.','.$articleTitle.','.$login_id.')">
                            <button id="assign_category_'.$article_id.'" class="btn btn-success btn-xs mtop-6">
                                <i class="fa fa-plus"></i>&nbsp;&nbsp;Assign category
                            </button>
                        </a>
                    </center>';
                    
                    $conn->close();
                    return trim($actions);
                }
                $conn->close();
                return "";
            }
        }
        
         function mturkWritersApprovedRewrites($worker_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = '$worker_id' AND status = '1' AND (updated_at >= '$dF_final' AND updated_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
                
        }
        
        function mturkWritersPendingRewrites($worker_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = '$worker_id' AND status = '0' AND (updated_at >= '$dF_final' AND updated_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
        
        function mturkWritersRejectedRewrites($worker_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = '$worker_id' AND (status= '2' OR status= '3') AND (updated_at >= '$dF_final' AND updated_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
        
        
        function writersApproveRewrites($user_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = '$user_id' AND status = '1' AND (created_at >= '$dF_final' AND created_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
        
        function writersPendingRewrites($user_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = '$user_id' AND status = '0' AND (created_at >= '$dF_final' AND created_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
        
        function writersRejectedRewrites($user_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = '$user_id' AND (status= '2' OR status= '3') AND (created_at >= '$dF_final' AND created_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
        
        function editorApprovedRewrites($user_id,$dateFrom,$dateTo){
            
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE reviewed_by = '$user_id' AND status = '1' AND (updated_at >= '$dF_final' AND updated_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
            
        }
        
        function editorRejectedRewrites($user_id,$dateFrom,$dateTo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                
                $dF = explode("/",$dateFrom);
                $dF_final = $dF[2]."-".$dF[0]."-".$dF[1]." 00:00:00";
                
                $dT = explode("/",$dateTo);
                $dT_final = $dT[2]."-".$dT[0]."-".$dT[1]." 23:59:59";
                
                
                $sql = "SELECT * FROM tbl_rewrites WHERE reviewed_by = '$user_id' AND (status = '2' OR status = '3') AND (updated_at >= '$dF_final' AND updated_at <= '$dT_final')";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
    
        function fetchPerformer($role_id,$user_id=null){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($user_id != null){
                    $aList = array();
                    foreach($role_id as $id){
                        $id = mysqli_real_escape_string($conn,$id);
                        $sql = "SELECT * FROM tbl_assignments WHERE role_id = '$id' AND user_id='$user_id'";
                        $result = $conn->query($sql);
                        if(!empty($result) && $result->num_rows > 0){
                            
                            while($row = $result->fetch_assoc()){
                                $aList[] = $row;
                            }
                        } else{

                        }
                    }
                    if(empty($aList)){
                        $conn->close();
                        return array();
                    } else{
                        $conn->close();
                        return $aList;
                    }
                } else {
                    $aList = array();
                    foreach($role_id as $id){
                        $id = mysqli_real_escape_string($conn,$id);
                        $sql = "SELECT * FROM tbl_assignments WHERE role_id = '$id'";
                        $result = $conn->query($sql);
                        if(!empty($result) && $result->num_rows > 0){
                            
                            while($row = $result->fetch_assoc()){
                                $aList[] = $row;
                            }
                        } else{

                        }
                    }
                    if(empty($aList)){
                        $conn->close();
                        return array();
                    } else{
                        $conn->close();
                        return $aList;
                    }
                }
            }
        }
        
        
        // FUNCIONS FOR PERFORMANCE REPORT
        
        // FUNCTIONS FOR IMPORTING ARTICLES
        
        function importArticle($data){
            $article_title = $this->getTitle($data['importArticle']);
            $article_intro = $this->getIntroduction($data['importArticle']);
            $article_items = $this->getItems($data['importArticle']);
            $article_conclu = $this->getConclusion($data['importArticle']);
            
            if(empty($article_title) OR empty($article_intro) OR empty($article_items) OR empty($article_conclu) OR $article_title == "Wrong Format" OR $article_intro == "Wrong Format" OR $article_items == "Wrong Format" OR $article_conclu == "Wrong Format" ){
                $message = array("type"=>"false", "message"=>"Failed to import. Article component is missing.");
                return json_encode($message);
            } else {
                if(preg_match('/\[article-title\](.*)\[article-title\]/',$article_title,$matchesTitle)){
                $data = array(trim($matchesTitle[1]),$data['user_id'],$data['category']);
                $article_id = $this->importInsertArticleTitle($data);
              
                if($article_id == "articleExist") {
                    $message = array("type"=>"false", "message"=>"Import failed. Article already exist.");
                    return json_encode($message);
                } elseif($article_id != false) {
                    $a1 = false;
                    $a2 = false;
                    $a3 = false;
                    
                    // article intro
                    $article_intro = trim($article_intro);
                    $content_id = 0;
                    $x = 0;
                    $parNo = 1;
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_intro) as $line){
                        if($line != ""){
                            if($x == 0) { // this is the title
                                preg_match('/\[title\]((?s).*)\[title\]/', $line, $matchesT);
                                if(!empty($matchesT[1])){
                                    $title = trim($matchesT[1]);
                                    $data = array("article_id"=>$article_id, "type"=>"introduction","title"=>$title,"order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $this->importInsertIntroductionTitle($data);
                                } else{
                                    $data = array("article_id"=>$article_id, "type"=>"introduction","title"=>"none","order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $this->importInsertIntroductionTitle($data);
                                    
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$parNo,"paragraph"=>$line);
                                    $parNo++;
                                    //print_r($data);
                                    //echo "<br>";
                                    $res = $this->importInsertIntroSentences($data);
                                    if($res){
                                        $a1 = true;
                                    } else {
                                        //Continue by deleting the article 
                                        $message = array("type"=>"false", "message"=>"Import Failed. The introduction paragraph does not contain a [sentence-end] delimeter.");
                                        $this->deleteArticleAndComponents($article_id);
                                        return json_encode($message);
                                    }
                                    
                                }
                            } else{
                                if($content_id != 0){
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$parNo,"paragraph"=>$line);
                                    $parNo++;
                                    //print_r($data);
                                    //echo "<br>";
                                    $res = $this->importInsertIntroSentences($data);
                                    if($res){
                                        $a1 = true;
                                    } else {
                                        //Continue by deleting the article 
                                        $message = array("type"=>"false", "message"=>"Import Failed. The introduction paragraph does not contain a [sentence-end] delimeter.");
                                        $this->deleteArticleAndComponents($article_id);
                                        return json_encode($message);
                                    }
                                } else{
                                    // Continue here by deleting the article.
                                    $this->deleteArticleAndComponents($article_id);
                                    $message = array("type"=>"false", "message"=>"Import Failed, (Introduction error) something went wrong. Please try again");
                                    return json_encode($message);
                                }
                            
                            }
                            $x++;
                        } 
                    }
                    // article intro 
                
                    // article items
                    $article_items= trim($article_items);
                    $content_id=0;
                    $subHOrder = 1;
                    $parNo = 1;
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_items) as $line){
                        if($line != ""){
                            if(preg_match('/\[title\]((?s).*)\[title\]/', $line, $matchesT)){
                                $title = trim($matchesT[1]);
                                $data = array("article_id"=>$article_id, "type"=>"subheading","title"=>$title,"order_no"=>$subHOrder);
                                //print_r($data);
                                //echo "<br>";
                                $content_id = $this->importInsertItemsTitle($data);
                                $subHOrder++;
                                $parNo=1;
                                
                            } else{
                                if($content_id != false) {
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$parNo,"paragraph"=>$line);
                                    $res = $this->importInsertIntroSentences($data);
                                    if($res){
                                        $a2 = true;
                                        } else {
                                            $a2 = false;
                                            //Continue by deleting the article 
                                            $message = array("type"=>"false", "message"=>"Import Failed. (Item error) The paragraph does not contain a [sentence-end] delimeter.");
                                            $this->deleteArticleAndComponents($article_id);
                                            return json_encode($message);
                                        }
                                    $parNo++;
                                    //print_r($data);
                                    //echo "<br>";
                                    } else{
                                    $a2 = false;
                                    // Continue here by deleting the article.
                                    $this->deleteArticleAndComponents($article_id);
                                    $message = array("type"=>"false", "message"=>"Import Failed. (Item error) something went wrong. Please try again");
                                    return json_encode($message);
                                }
                                
                            }
                        }
                        
                    }
                    // article items
                
                    //article conclusion
                    $article_conclu = trim($article_conclu);
                    $content_id = 0;
                    $x = 0;
                    $paragraph = 1;
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $article_conclu) as $line){
                        if($line != ""){
                            if($x == 0) { // this is the title
                                preg_match('/\[title\]((?s).*)\[title\]/', $line, $matchesT);
                                if(!empty($matchesT[1])){
                                    $title = trim($matchesT[1]);
                                    $data = array("article_id"=>$article_id, "type"=>"conclusion","title"=>$title,"order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $this->importInsertConclusionTitle($data);
                                } else{
                                    $data = array("article_id"=>$article_id, "type"=>"conclusion","title"=>"none","order_no"=>"1");
                                    //print_r($data);
                                    //echo "<br>";
                                    $content_id = $this->importInsertConclusionTitle($data);
                                    
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$paragraph,"paragraph"=>$line);
                                    //print_r($data);
                                    //echo "<br>";
                                    $res = $this->importInsertIntroSentences($data);
                                    if($res){
                                        $a3 = true;
                                    } else {
                                        //Continue by deleting the article 
                                        $message = array("type"=>"false", "message"=>"Import Failed. The conclusion paragraph does not contain a [sentence-end] delimeter.");
                                        $this->deleteArticleAndComponents($article_id);
                                        return json_encode($message);
                                    }
                                    $paragraph++;
                                    
                                }
                            } else{
                                if($content_id != false){
                                    $data = array("content_id"=>$content_id,"article_id"=>$article_id,"paragraph_no"=>$paragraph,"paragraph"=>$line);
                                    //print_r($data);
                                    //echo "<br>";
                                    $res = $this->importInsertIntroSentences($data);
                                    if($res){
                                        $a3 = true;
                                    } else {
                                        //Continue by deleting the article 
                                        $message = array("type"=>"false", "message"=>"Import Failed. The conclusion paragraph does not contain a [sentence-end] delimeter.");
                                        $this->deleteArticleAndComponents($article_id);
                                        return json_encode($message);
                                    }
                                    $paragraph++;
                                } else{
                                    // Continue here by deleting the article.
                                    $this->deleteArticleAndComponents($article_id);
                                    $message = array("type"=>"false", "message"=>"Import failed. (Conclusion error). Please try again");
                                    return json_encode($message);
                                }
                                
                            }
                            $x++;
                        }
                    }
                    //article conclusion
                
                    if($a1 == TRUE AND $a2 == TRUE AND $a3 == TRUE){
                        $message = array("type"=>"true", "message"=>"Successfully imported the article. Go to article list?");
                        return json_encode($message);
                    }
                } else{
                    $message = array("type"=>"false", "message"=>"Import failed, something went wrong. Please try again");
                    return json_encode($message);
                }
     
            } else{
                $message = array("type"=>"false", "message"=>"Import failed. Article title wrong format.");
                return json_encode($message);
            } 
                
            }
        }
        
        
        function getConclusion($article){
            if(preg_match('/\[article-conclusion\]((?s).*)\[article-conclusion\]/', $article, $matchesConclu)){  // get article conclusion
                if(isset($matchesConclu[1])){
                    $article_conclu = $matchesConclu[1];
                    return $article_conclu;
                } else { return ""; }
            } else{
                return "Wrong Format";
            }
            
        }
        
        function getItems($article) {
            if(preg_match('/\[article-items\]((?s).*)\[article-items\]/', $article, $matchesItems)){ //get article items
                if(isset($matchesItems[1])){
                    $article_items = $matchesItems[1];
                    return $article_items;
                } else { return ""; }
            } else{
                return "Wrong Format";
                
            }
        }
        
        function getIntroduction($article){
            if(preg_match('/\[article-intro\]((?s).*)\[article-intro\]/', $article, $matchesIntro)){ // get introduction
                if(isset($matchesIntro[1])){
                    $article_intro = $matchesIntro[1];
                    return $article_intro;
                } else { return ""; }
            }else{
                return "Wrong Format";
            }
        }
        
        function getTitle($article){
            if(preg_match('/\[article-title\](.*)\[article-title\]/',$article,$matchesTitle)){ // get title 
                if(isset($matchesTitle[0])){
                    $article_title = $matchesTitle[0];
                    return $article_title;
                }else { return ""; }
            } else{
                return "Wrong Format";
            }
        }
        
        function fetchImportedArticle($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $sql = "SELECT * FROM tbl_imported_articles WHERE id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $conn->close();
                    return $result->fetch_assoc();
                }
                $conn->close();
                return array();
                
            }
        }
        
        
        function insertImportedArticle($article,$user_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $article = mysqli_real_escape_string($conn,$article);
                $user = mysqli_real_escape_string($conn,$user_id);
                $created_at = date("Y-m-d H:i:s");
                $sql = "INSERT INTO tbl_imported_articles(article,user_id,created_at) VALUES('$article','$user','$created_at')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else{
                    $conn->close();
                    return false;
                }
                
            }
        }
        
        function importInsertConclusionTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $article_id = mysqli_real_escape_string($conn,$data['article_id']);
                $type = mysqli_real_escape_string($conn,$data['type']);
                $title = mysqli_real_escape_string($conn,$data['title']);
                $order = mysqli_real_escape_string($conn,$data['order_no']);
                
                $sql = "INSERT INTO tbl_contents(article_id,type,title,order_no) VALUES('$article_id','$type','$title','$order')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        
        function importInsertItemsTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $article_id = mysqli_real_escape_string($conn,$data['article_id']);
                $type = mysqli_real_escape_string($conn,$data['type']);
                $title = mysqli_real_escape_string($conn,$data['title']);
                $order = mysqli_real_escape_string($conn,$data['order_no']);
                $sql = "INSERT INTO tbl_contents(article_id,type,title,order_no) VALUES('$article_id','$type','$title','$order')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        
        function importInsertIntroSentences($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $content_id = mysqli_real_escape_string($conn,$data['content_id']);
                $article_id = mysqli_real_escape_string($conn,$data['article_id']);
                $parNo = mysqli_real_escape_string($conn,$data['paragraph_no']);
                
                if(strpos($data['paragraph'], "[sentence-end]") !== false) {} else { $conn->close(); return false; } // 
                
                $sentences = explode("[sentence-end]",trim($data['paragraph']));
                $sentences = array_filter($sentences);
                $orderNo = 0;
                foreach($sentences as $sentence){
                    
                    $sentence = trim($sentence);
                    
                    $pdSentence = mysqli_real_escape_string($conn,$sentence);
                    $orderNo++;
                    $sql = "INSERT INTO tbl_sentences(content_id,article_id,order_no,paragraph_no,sentence) VALUES('$content_id','$article_id','$orderNo','$parNo','$pdSentence')";
                    if($conn->query($sql) == TRUE){
                    } else{
                        $conn->close();
                        return false;
                    }
                }
                $conn->close();
                return true;
                
            }
            
        }
        
        function deleteArticleAndComponents($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "DELETE FROM tbl_new_articles WHERE id='$id'";
                $conn->query($sql);
                $sql = "DELETE FROM tbl_contents WHERE article_id='$id'";
                $conn->query($sql);
                $sql = "DELETE FROM tbl_sentences WHERE article_id='$id'";
                $conn->query($sql);
            }
        }
        
        
        function importInsertIntroductionTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $article_id = mysqli_real_escape_string($conn,$data['article_id']);
                $type = mysqli_real_escape_string($conn,$data['type']);
                $title = mysqli_real_escape_string($conn,$data['title']);
                $order = mysqli_real_escape_string($conn,$data['order_no']);
                
                $sql = "INSERT INTO tbl_contents(article_id,type,title,order_no) VALUES('$article_id','$type','$title','$order')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        
        function importInsertArticleTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $writer_id = mysqli_real_escape_string($conn,$data[1]);
                $title = mysqli_real_escape_string($conn,$data[0]);
                $category = mysqli_real_escape_string($conn,$data[2]);
                $create_at = date("Y-m-d H:i:s");
                $updated_at = date("Y-m-d H:i:s");
                
                $artS = $this->fetchAllArticle();
                
                $title = trim($title);
                $titleS = strtolower($title);
                foreach($artS as $art) {
                    $arti = trim($art['title']);
                    $arti = strtolower($arti);
                    //return $arti . strtolower($data[0]);
                    if(strcmp(strtolower($data[0]),$arti) == 0){
                        //echo $title . $art;
                        $conn->close();
                        return "articleExist";
                    }
                }

                $sql = "INSERT INTO tbl_new_articles(title,user_id,created_at,updated_at,status,category_id)VALUES('$title','$writer_id','$create_at','$updated_at','1',$category)";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        
        // FUNCTIONS FOR IMPORTING ARTICLES
        
        
        /**** READY FOR REWRITE TABLE ******/
        
        function readyForRewriteTbActions($article_id){
            return "<center><a href='?page=rewritingNew&articleId=$article_id' class='btn btn-success btn-xs'><i class='fa fa-magic'></i>&nbsp;Rewrite</a></center>";
        }
        
        function fetchArticleCreator(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $sql = "SELECT user_id from tbl_assignments WHERE role_id='1' OR role_id='3' OR role_id='4'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    
                    $outPut = array();
                    $i = 0;
                    foreach($res as $item){
                        
                        $user = $this->getUserById($item['user_id']);
                        $outPut[$i]['user_id'] = $item['user_id'];
                        $outPut[$i]['name'] = $user['fname']." ".$user['lname'];
                        $i++;
                    }
                    
                    $conn->close();
                    return $outPut;
                    
                    
                }
                $conn->close();
                return array();

                }
        }
        
        /**** READY FOR REWRITE TABLE ******/
        
        
        /**** REWRITTEN SENTENCES TABLE ****/

        function fetchArticleTitleForRewroteSentence($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $id = mysqli_real_escape_string($conn,$id);
                $sql = "SELECT title FROM tbl_new_articles WHERE id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $conn->close();
                    return htmlentities($row['title']);
                } else{
                    $conn->close();
                    return "";
                }
            }
        }
        
        function fetchSentenceForRewrotedSentence($id) {
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $id = mysqli_real_escape_string($conn,$id);
                $sql = "SELECT * FROM tbl_sentences WHERE id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $conn->close();
                    return ($row['sentence']);
                } else{
                    $conn->close();
                    return "";
                }
            }
        }
        
        function rewrotedSentenceStatus($status){
            if($status == 0) { //pending
                return '<b><p class="text-light-blue">PENDING</p></b>';
            } else if($status == 1) { //approved
                return '<b><p class="text-green">APPROVED</p></b>';
            } else if($status == 2) { //rejected
                return '<b><p class="text-red">REJECTED</p></b>';
            }  else {
                return '<b><p class="text-orange">REJECTED & REWRITTEN</p></b>';
            }
            
        }
        
        function rewrotedSentencesTbActions($rewrite_id){
            //return "<center><a href='?page=rewritten-sentence&id=$id'><button  class='btn btn-primary btn-xs mtop-6'><i class='fa fa-eye'></i>&nbsp;&nbsp;View</button></a></center>";
            
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_rewrites WHERE id='$rewrite_id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $res = $result->fetch_assoc();
                    
                    
                    $origSentence = htmlspecialchars(json_encode(iccaFunc::convert_smart_quotes(iccaFunc::htmlallentities(iccaFunc::fetchSentenceForRewrotedSentence($res['sentence_id'])))));
                    $rewroteSentence = htmlspecialchars(json_encode(iccaFunc::convert_smart_quotes(iccaFunc::htmlallentities($res['sentence']))));
                    
                    // view
                    $actions = "<center><a href='?page=rewritten-sentence&id=".$res['id']."' target='_blank'><button  class='btn btn-primary btn-xs mtop-6'><i class='fa fa-eye'></i>&nbsp;&nbsp;View</button></a>";
                    
                    // edit
                    $numOfApproveRewrites = iccaFunc::numOfApprovedRewrites($res['sentence_id']);
                    if(($res['status'] == 1 && $numOfApproveRewrites < 3) || $res['status'] == 2 || $rewrite['status'] == 0) {
                        
                    $actions .= '<a href="javascript:showModal('.$res['id'] .','.$res['status'].','.$origSentence.','.$rewroteSentence.')">
                                <button id="edit_'. $res['id'].'" class="btn btn-warning btn-xs mtop-6">
                                <i class="fa fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                </button></a>';
                            
                    }     
                    
                    //delete
                    $actions .= "&nbsp;<a class='deleteAction' href='javascript:confirmDelete(".$res['id'].")'><button class='btn btn-danger btn-xs mtop-6'><i class='fa fa-trash'></i>&nbsp;&nbsp;Delete</button></a>";
                    
                    
                    $user = iccaFunc::getUserById($res['user_id']);
                    $fullName = $user['fname']." ".$user['lname'];
                    $articleTitle = iccaFunc::fetchArticleTitleForRewroteSentence($res['article_id']);
                    
                    //approve
                    if($res['status'] != 1) { 
                        $actions .= '<a class="approveAction" href="javascript:approveRewrite('.$res['id'].',\''.preg_replace('/\s+/', ' ', $fullName).'\',\''.addslashes($articleTitle).'\','.$res['status'].')">
                                     <button class="btn btn-success btn-xs mtop-6">
                                        <i class="fa fa-check"></i>&nbsp;&nbsp;Approve
                                     </button>
                                     </a>';
                    }
                    
                    //reject
                    if($res['status'] == 0) {
                        $actions .= '<a href="javascript:rejectRewrite('.$res['id'].',\''.preg_replace('/\s+/', ' ', $fullName).'\',\''.addslashes($articleTitle).'\','.$res['status'].')">
                                     <button class="btn btn-danger btn-xs mtop-6">
                                     <i class="fa fa-times"></i>&nbsp;&nbsp;Reject
                                     </button></a>';
                    } 
                    
                    //rewrite again (for rejected rewrites)
                    if($res['status'] == 2 && $res['user_id'] !== $_SESSION['login_id']) {
                        $actions .= '<a href="javascript:showRewriteModal('.$res['id'] .','.$origSentence.')">
                            <button class="btn bg-orange btn-xs mtop-6">
                            <i class="fa fa-redo-alt"></i>&nbsp;&nbsp;Rewrite
                            </button></a></center>';
                    } else {
                        $actions .= '</center>';
                    }
                    
                    $conn->close();
                    return trim($actions);
                }
                $conn->close();
                return "";
                
            }
        }
        
        function numOfApprovedRewrites($sentence_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_rewrites WHERE sentence_id='$sentence_id' AND status='1'";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            }
        }
        
        
        /**** REWRITTEN SENTENCES TABLE ****/
        
        
        
        function isReadyArticle($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $aId = mysqli_real_escape_string($conn,$id);
                $sql = "SELECT * FROM tbl_contents WHERE type='introduction' AND article_id='$aId'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $sql = "SELECT * FROM tbl_contents WHERE type='subheading' AND article_id='$aId'";
                    $result= $conn->query($sql);
                    if($result->num_rows > 0){
                        $sql = "SELECT * FROM tbl_contents WHERE type='conclusion' AND article_id='$aId'";
                        $result= $conn->query($sql);
                        if($result->num_rows > 0){
                            $conn->close();
                            return true;
                        } else{
                            $conn->close();
                            return false;
                        }
                    } else{
                        $conn->close();
                        return false;
                    }
                }else{
                    $conn->close();
                    return false;
                }
                
            }
        }
        
        function perfectTense($data){
            $apiCred = $this->getPerfectTenseCred();
            $text = trim($data['text']);
            $header = array();
            $header[] = "Authorization: ".$apiCred[0]['username'];
            $header[] = "AppAuthorization: ".$apiCred[0]['api_key'];
            $header[] = "Content-Type: application/json";
            
            $data = array("text"=>"$text","responseType"=>array("corrected","grammarScore","rulesApplied","offset","summary"));

            //echo json_encode($data);
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://api.perfecttense.com/correct");
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt ($curl, CURLOPT_POST, 1);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, json_encode($data));
            
            $result = curl_exec($curl);
            curl_close($curl);
            $result = str_replace("1",$result);
            return trim($result);
            
        }
        
        function fetchAllArticleForRewrite(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $sql = "SELECT * FROM tbl_new_articles WHERE status='1' AND ready_status='1' ORDER BY created_at DESC";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        
        function switchStatus($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $user = $this->getUserById($data['user_id']);
                $art_id = mysqli_real_escape_string($conn,$data['article_id']);
                $ready_status = mysqli_real_escape_string($conn,$data['ready']);
                $created_at = date("Y-m-d H:i:s");
                $action = $user['fname']." ".$user['lname']."updated the status of article.";
                $action = mysqli_real_escape_string($conn,$action);
                $sql = "INSERT INTO tbl_logs(article_id,created_at,action) VALUES('$aId','$created_at','$action')";
                $conn->query($sql);
                
                $sql2 = "UPDATE tbl_new_articles SET ready_status='$ready_status' WHERE id='$art_id'";
                if($conn->query($sql2) === TRUE){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        
        function processSpin($data){
            
            $apiCred = $this->getWordAiCredentials();
            
            $email = $apiCred[0]['username'];
            $password = $apiCred[0]['api_key'];
            $quality = "Very Readable";
            $text = $data['sentence'];
            
            return $this->apiWordAI($text,$quality,$email,$password);
        }
        
        function apiWordAI($text,$quality,$email,$pass){
            if(isset($text) && isset($quality) && isset($email) && isset($pass)){

            $text = urlencode($text);
            $ch = curl_init('http://wordai.com/users/turing-api.php');
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
            curl_setopt ($ch, CURLOPT_POST, 1);
    
            curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&returnspin=true&nooriginal=on&perfect_tense=correct&output=json");
    
            $result = curl_exec($ch);
    
            curl_close ($ch);
    
            return $result;
            }

        else{
            return 'Error: Not All Variables Set!';
        }
    }
        
        function fetchConclusion($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$article_id' AND type='conclusion'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
                
            }
        }
        
        function insertConclusion($data,$article_id,$type,$concluTitle){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                unset($data['article_id']);
                unset($data['formG']);
                unset($data['type']);
                unset($data['conclusion_title']);
                
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $type = mysqli_real_escape_string($conn,$type);
                $concluTitle = mysqli_real_escape_string($conn,$concluTitle);
                
                $sql = "INSERT INTO tbl_contents(article_id,type,title,order_no) VALUES ('$article_id','$type','$concluTitle','1')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $paragraph_no = 0;
                    foreach($data as $item){
                        $paragraph_no++;
                        /*
                        $item = rtrim($item) . " "; // add whitespace at the end of string
                        $needles = array('! ','? ','. ','" ');
                        $lastPos = 0;
                        $positions = array();
                        
                        foreach($needles as $needle){
                            while (($lastPos = strpos($item, $needle, $lastPos))!== false) {
                                $item = substr_replace($item, ":::", ($lastPos+1), 0);
                                $lastPos = $lastPos + strlen($needle);
                            }
                        }
                        $item = trim($item); */
                        //$item = preg_replace('/\'[^\']*\'(*SKIP)(*F)|:::/', '',$item);
                        $sentences = explode("[sentence-end]",$item);
                        $sentences = array_filter($sentences);
                        //print_r($sentences);
                        $order_no = 0;
                        foreach($sentences as $sentence){
                            $sentence = trim($sentence);
                            
                            $order_no++;
                            $pdSentence = mysqli_real_escape_string($conn,$sentence);
                            $sql = "INSERT INTO tbl_sentences(content_id,article_id,order_no,paragraph_no,sentence) VALUES('$last_id','$article_id','$order_no','$paragraph_no','$pdSentence')";
                            if($conn->query($sql) == TRUE){
                                $insert_status = true;
                            } else{
                                $insert_status = false;
                            }
                        } 
                    }
                }else{
                    $conn->close();
                    return "Error: not able to create the introduction title!";
                    
                }
                
                if($insert_status){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return "failed";
                }
                
                
            }
        }
        
        function editArticleTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $aId = mysqli_real_escape_string($conn,$data['article_id']);
                $from_ = mysqli_real_escape_string($conn,$data['previous_title']);
                $to_ = mysqli_real_escape_string($conn,$data['changes']);
                $created_at = date("Y-m-d H:i:s");
                $user = $this->getUserById($data['user_id']);
                $action = $user['fname']." ".$user['lname']." change the article name.";
                $sql = "INSERT INTO tbl_logs(article_id,created_at,action,from_,to_) VALUES('$aId','$created_at','$action','$from_','$to_')";
                $conn->query($sql);
                
                $sql2 = "UPDATE tbl_new_articles SET title='$to_' WHERE id='$aId'";
                if($conn->query($sql2) == TRUE){
                    $conn->close();
                    return "success:editarticletitle";
                } else{
                    $conn->close();
                    return false;
                }
                
            }
        }
        
        function editSentence($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $user = $this->getUserById($data['user_id']);
                $sentence_id = mysqli_real_escape_string($conn,$data['chosenSentenceId']);
                $from_ = mysqli_real_escape_string($conn,$data['previous_sentence']);
                $to_ = mysqli_real_escape_string($conn,$data['editedSentence']);
                $created_at = date("Y-m-d H:i:s");
                $action = $user['fname']." ".$user['lname']." edited sentence.";
                $action = mysqli_real_escape_string($conn,$action);
                $sql = "INSERT INTO tbl_logs(sentence_id,created_at,action,from_,to_) VALUES('$sentence_id','$created_at','$action','$from_','$to_')";
                $conn->query($sql);
                
                $sentence = mysqli_real_escape_string($conn,$data['editedSentence']);
                
                $sql2 = "UPDATE tbl_sentences SET sentence='$sentence' WHERE id='$sentence_id'";
                if($conn->query($sql2) == TRUE) {
                    $conn->close();
                    return "success:editsentence";
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function editIntroductionTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $introId = mysqli_real_escape_string($conn,$data['introId']);
                $origTitle = mysqli_real_escape_string($conn,$data['orig_title']);
                $changes = mysqli_real_escape_string($conn,$data['changes']);
                $created_at = date("Y-m-d H:i:s");
                $user = $this->getUserById($data['user_id']);
                $action = $user['fname']." ".$user['lname']." changed the introduction title.";
                $action = mysqli_real_escape_string($conn,$action);
                
                $sql = "INSERT INTO tbl_logs(content_id,created_at,action,from_,to_) VALUES('$introId','$created_at','$action','$origTitle','$changes')";
                $conn->query($sql);
                
                $sql2 = "UPDATE tbl_contents SET title='$changes' WHERE id='$introId'";
                if($conn->query($sql2) == TRUE){
                    $conn->close();
                    return "success:editintrotitle";
                }else{
                    $conn->close();
                    return false;
                }
                
            }
        }
        
        function editSubheading($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $kaboom = explode("::",$data['subHId']);
                $subId = mysqli_real_escape_string($conn,$kaboom[0]);
                $origTitle = mysqli_real_escape_string($conn,$kaboom[1]);
                $changes = mysqli_real_escape_string($conn,$data['changes']);
                $created_at = date("Y-m-d H:i:s");
                $user = $this->getUserById($data['user_id']);
                $action = $user['fname']." ".$user['lname']." changed the subheading title.";
                $action = mysqli_real_escape_string($conn,$action);
                
                $sql = "INSERT INTO tbl_logs(content_id,created_at,action,from_,to_) VALUES('$subId','$created_at','$action','$origTitle','$changes')";
                $conn->query($sql);
                
                $sql2 = "UPDATE tbl_contents SET title='$changes' WHERE id='$subId'";
                if($conn->query($sql2) == TRUE){
                    $conn->close();
                    return "success:editsubhtitle";
                }else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function editArticleConcluTitle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $introId = mysqli_real_escape_string($conn,$data['concluId']);
                $origTitle = mysqli_real_escape_string($conn,$data['orig_title']);
                $changes = mysqli_real_escape_string($conn,$data['changes']);
                $created_at = date("Y-m-d H:i:s");
                $user = $this->getUserById($data['user_id']);
                $action = $user['fname']." ".$user['lname']." changed the conclusion title.";
                $action = mysqli_real_escape_string($conn,$action);
                
                $sql = "INSERT INTO tbl_logs(content_id,created_at,action,from_,to_) VALUES('$introId','$created_at','$action','$origTitle','$changes')";
                $conn->query($sql);
                
                $sql2 = "UPDATE tbl_contents SET title='$changes' WHERE id='$introId'";
                if($conn->query($sql2) == TRUE){
                    $conn->close();
                    return "success:editconclusiontitle";
                }else{
                    $conn->close();
                    return false;
                }
                
            }
        }
        
        function fetchArtConclusion($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $aId = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$aId' AND type='conclusion'";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        
        function fetchArticleByid($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $aId = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_new_articles WHERE id='$aId'";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        function fetchSentenceByContentIdAndParNo($content_id,$parNo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $content_id = mysqli_real_escape_string($conn,$content_id);
                $parNo = mysqli_real_escape_string($conn,$parNo);
                $sql = "SELECT * FROM tbl_sentences WHERE content_id='$content_id' AND paragraph_no='$parNo' ORDER BY order_no ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        
        function fetchContentByComponent($article_id,$component){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $component = mysqli_real_escape_string($conn,$component);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$article_id' AND type='$component'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row=$result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        
        function fetchAllContent($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$article_id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            } 
        }
        
        function deleteArticle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $user = $this->getUserById($data['user_id']);
                $article_id = mysqli_real_escape_string($conn,$data['article_id']);
                $created_at = date("Y-m-d H:i:s");
                $action = $user['fname']." ".$user['lname']." deleted an article.";
                $from_ = "";
                $to_ = "";
                $action = mysqli_real_escape_string($conn,$action);
                
                $sql = "INSERT INTO tbl_logs(article_id,created_at,action,from_,to_) VALUES('$article_id','$created_at','$action','$from_',$to_)";
                $conn->query($sql);
                
                $sql2 = "UPDATE tbl_new_articles SET status='0' WHERE id='$article_id'";
                if($conn->query($sql2) == TRUE){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
                
            }
        }
        
        
        function deleteRewrite($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $user = $this->getUserById($data['user_id']);
                $sentence = $this->fetchRewrite($data['rewrite_id']);

                $action = $user['fname'].$user['lname']." deleted rewrite_id = ".$data['rewrite_id'].".";
                $from_ = $sentence[0]['sentence'];
                $to_ = $sentence[0]['sentence'];
                
                $rewrite_id = mysqli_real_escape_string($conn,$data['rewrite_id']);
                $action = mysqli_real_escape_string($conn,$action);
                $from_ = mysqli_real_escape_string($conn,$from_);
                $to_ = mysqli_real_escape_string($conn,$to_);
                $created_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_logs(rewrite_id, created_at, action, from_, to_) VALUES('$rewrite_id','$created_at','$action','$from_','$to_')";
                $conn->query($sql);
                
                $sql2 = "DELETE FROM tbl_rewrites WHERE id='$rewrite_id'";
                if($conn->query($sql2) === TRUE) {
                    $conn->close();
                    return true;
                }
                $conn->close();
                return false;
                
            }
        }
        
        function fetchRewrite($rewriteId){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $rewriteId = mysqli_real_escape_string($conn,$rewriteId);
                $sql = "SELECT * FROM tbl_rewrites WHERE id='$rewriteId'";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ) {
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                } 
                $conn->close();
                return array();
            }
        }
        

        function insertEditedSentence($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                
                $user = $this->getUserById($data['user_id']);
                
                $rewriteId = mysqli_real_escape_string($conn,$data['sentenceId']);
                $created_at = date("Y-m-d H:i:s");
                $action = $user['fname']." ".$user['lname']." edited a rewritten sentence";
                $from_ = mysqli_real_escape_string($conn,$data['orig_sentence']);
                $to_ = mysqli_real_escape_string($conn,$data['sentence']);
                
                $sql = "INSERT INTO tbl_logs(rewrite_id, created_at, action, from_, to_) VALUES('$rewriteId','$created_at','$action','$from_','$to_')";
                $conn->query($sql);
                
                
                $sql2 = "UPDATE tbl_rewrites SET sentence='$to_', updated_at='$created_at' WHERE id=$rewriteId";
                
                if($conn->query($sql2) === TRUE){
                    return true;
                } else{
                    return false;
                }
            }
        }
        
        
        function fetchRewrotedSentence($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sentenceId = mysqli_real_escape_string($conn,$data['sentenceId']);
                $orderNo = mysqli_real_escape_string($conn,$data['orderNo']);
                $parNo = mysqli_real_escape_string($conn,$data['parNo']);
                $user_id = mysqli_real_escape_string($conn,$data['user_id']);
                
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id='$user_id' AND sentence_id='$sentenceId' AND order_no='$orderNo' AND paragraph_no='$parNo' ORDER BY created_at ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return json_encode($res); 
                } else{
                }
               
                $conn->close();
                return "empty";
            } 
            
        }
        
        function insertRewrittenSentence($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $user_id = mysqli_real_escape_string($conn,$data['editor']);
                $article_id = mysqli_real_escape_string($conn,$data['article_id']);
                $sentence_id = mysqli_real_escape_string($conn,$data['sentenceId']);
                $order_no = mysqli_real_escape_string($conn,$data['sentenceOrderNo']);
                $paragNo = mysqli_real_escape_string($conn,$data['sentenceParNo']);
                $sentence = mysqli_real_escape_string($conn,$data['sentence']);
                $status = 0;
                $reviewed_by = 0;
                $created_at = date("Y-m-d H:i:s");
                $updated_at = date("Y-m-d H:i:s");
                
                $article = iccaFunc::fetchArticleByid($data['article_id']);
                $category_id = mysqli_real_escape_string($conn,$article['category_id']);
                
                $sql = "INSERT INTO tbl_rewrites(user_id,article_id,category_id,sentence_id,order_no,paragraph_no,sentence,status,reviewed_by,created_at,updated_at) 
                        VALUES ('$user_id','$article_id','$category_id','$sentence_id','$order_no','$paragNo','$sentence','$status','$reviewed_by','$created_at','$updated_at')";
                        
                if($conn->query($sql) ==  TRUE) {
                    $conn->close();
                    return true;
                }
                $conn->close();
                return false;
            }          
        }
        
        function fetchParagraph($contId,$parNo){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $contId = mysqli_real_escape_string($conn,$contId);
                $parNo = mysqli_real_escape_string($conn,$parNo);
                $sql = "SELECT * FROM tbl_sentences WHERE content_id='$contId' AND paragraph_no='$parNo' ORDER BY order_no ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        
        function fetchArticle($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_new_articles WHERE id='$article_id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        function fetchCategoryList($id=""){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                    if($id=="")$condition ="";
                    else $condition = "WHERE id=".$id;
                    $sql = "SELECT * FROM tbl_category $condition ORDER BY name ASC";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $res = array();
                        while($row = $result->fetch_assoc()){
                            $res[] = $row;
                        }
                        $conn->close();
                        return $res;
                    }
                    $conn->close();
                    return array();
            }
        }
        function fetchAllArticle(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                    $sql = "SELECT * FROM tbl_new_articles WHERE status=1 ORDER BY created_at DESC";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        $res = array();
                        while($row = $result->fetch_assoc()){
                            $res[] = $row;
                        }
                        $conn->close();
                        return $res;
                    }
                    $conn->close();
                    return array();
            }
        }
        
        /*
        function fetchAllArticleWithoutCategory(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_new_articles WHERE status=1 AND category_id=0";
                $result= $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                } else{
                    $conn->close();
                    return array();
                }
                
            }
            
        } */
        
        
        function mergeSentences($sentences){
            $parNo = 1;
            $paragraphs = array();
            $mergeSentences = "";
            foreach($sentences as $sentence){
                if($parNo == $sentence['paragraph_no']){
                    $mergeSentences .= $sentence['sentence'] . " ";
                } else{
                    $paragraphs[] = $mergeSentences;
                    $mergeSentences = "";
                    $mergeSentences .= $sentence['sentence'] . " ";
                    $parNo = $parNo + 1;
                }
            }
            $paragraphs[] = $mergeSentences;
            return $paragraphs;
        }
        
        function fetchSubheadingTitle($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$article_id' AND type='subheading' ORDER BY order_no ASC";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $res = array();
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                    
                }
                $conn->close();
                return array();
            }
        }
        
        
        function insertSubheadingAndParagraph($data,$article_id,$subheading_title,$type){
            unset($data['subheading_title']);
            unset($data['article_id']);
            unset($data['type']);
            unset($data['formS']);
            
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                // check subheading count
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$article_id' AND type='subheading'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $subOrder_no = count($res);
                } else{
                    $subOrder_no = 0;
                }
                
                $insert_status = false;
                $type = mysqli_real_escape_string($conn,$type);
                $subTitle = mysqli_real_escape_string($conn,$subheading_title);
                $subOrder_no++;
            
                $sql = "INSERT INTO tbl_contents(article_id,type,title,order_no) VALUES('$article_id','$type','$subTitle','$subOrder_no')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $paragraph_no = 0;
                    foreach($data as $item){
                        $paragraph_no++;
                        /*
                        $item = rtrim($item) . " "; // add whitespace at the end of string
                        $needles = array('! ','? ','. ','" ');
                        $lastPos = 0;
                        $positions = array();
                        
                        foreach($needles as $needle){
                            while (($lastPos = strpos($item, $needle, $lastPos))!== false) {
                                $item = substr_replace($item, ":::", ($lastPos+1), 0);
                                $lastPos = $lastPos + strlen($needle);
                            }
                        }
                        $item = trim($item); */
                        //$item = preg_replace('/\'[^\']*\'(*SKIP)(*F)|:::/', '',$item);
                        
                        $sentences = explode("[sentence-end]",$item);
                        $sentences = array_filter($sentences);
                        //print_r($sentences);
                        $order_no = 0;
                        foreach($sentences as $sentence){
                            $sentence = trim($sentence);
                            
                            $order_no++;
                            $pdSentence = mysqli_real_escape_string($conn,$sentence);
                            $sql = "INSERT INTO tbl_sentences(content_id,article_id,order_no,paragraph_no,sentence) VALUES('$last_id','$article_id','$order_no','$paragraph_no','$pdSentence')";
                            if($conn->query($sql) == TRUE){
                
                                $insert_status = true;
                            } else{
            
                                $insert_status = false;
                            }
                        }
                        
                    }
                }else{
                    $conn->close();
                    return "Error: not able to create the subheading title!";
                }
                if($insert_status){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return "failed";
                }
                
            }
        }
        
        
        function fetchSentences($content_id, $order=null){
            if($order == null){
                $order = "ORDER BY paragraph_no ASC, order_no ASC";
            } 
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $content_id = mysqli_real_escape_string($conn,$content_id);
                $sql = "SELECT * FROM tbl_sentences WHERE content_id='$content_id' $order";
                
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $res = array();
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
            
        }
        
        
        function fetchArticleIntroduction($article_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $sql = "SELECT * FROM tbl_contents WHERE article_id='$article_id' AND type='introduction'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $res = array();
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                    
                }
                $conn->close();
                return array();
            }
        }

        function insertIntroductionTitleAndParagraphs($data,$article_id,$type,$introTitle){
            
            unset($data['introduction_title']);
            unset($data['article_id']);
            unset($data['type']);
            unset($data['formP']);
            
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $insert_status = false;
                $article_id = mysqli_real_escape_string($conn,$article_id);
                $type = mysqli_real_escape_string($conn,$type);
                $introTitle = mysqli_real_escape_string($conn,$introTitle);
                
                $sql = "INSERT INTO tbl_contents(article_id,type,title,order_no) VALUES('$article_id','$type','$introTitle','1')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $paragraph_no = 0;
                    foreach($data as $item){
                        $paragraph_no++;
                        /*
                        $item = rtrim($item) . " "; // add whitespace at the end of string
                        $needles = array('! ','? ','. ','" ');
                        $lastPos = 0;
                        $positions = array();
                        
                        foreach($needles as $needle){
                            while (($lastPos = strpos($item, $needle, $lastPos))!== false) {
                                $item = substr_replace($item, ":::", ($lastPos+1), 0);
                                $lastPos = $lastPos + strlen($needle);
                            }
                        }
                        $item = trim($item); */
                        //$item = preg_replace('/\'[^\']*\'(*SKIP)(*F)|:::/', '',$item);
                        
                        $sentences = explode("[sentence-end]",$item);
                        $sentences = array_filter($sentences);
                        //print_r($sentences);
                        $order_no = 0;
                        foreach($sentences as $sentence){
                            $sentence = trim($sentence);
                            $order_no++;
                            $pdSentence = mysqli_real_escape_string($conn,$sentence);
                            $sql = "INSERT INTO tbl_sentences(content_id,article_id,order_no,paragraph_no,sentence) VALUES('$last_id','$article_id','$order_no','$paragraph_no','$pdSentence')";
                            if($conn->query($sql) == TRUE){
                                $insert_status = true;
                            } else{
                                $insert_status = false;
                            }
                        } 
                    }
                }else{
                    $conn->close();
                    return "Error: not able to create the introduction title!";
                }
                if($insert_status){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return "failed";
                }
            } 
            
        }

        function addArticle($data){
            //print_r($data);
            
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                //echo $data['title'];
                $artS = $this->fetchAllArticle();
                $title = $data['title'];
                $title = trim($title);
                $title = strtolower($title);
                
                foreach($artS as $art) {
                    $art = trim($art['title']);
                    $art = strtolower($art);
                    if($title == $art){
                        //echo $title . $art;
                        $conn->close();
                        return "ArticleExist";
                    }
                }
                $writer_id = mysqli_real_escape_string($conn,$data['creator']);
                $title = mysqli_real_escape_string($conn,$data['title']);
                $create_at = date("Y-m-d H:i:s");
                $updated_at = date("Y-m-d H:i:s");
                $sql = "INSERT INTO tbl_new_articles(title,user_id,created_at,updated_at)VALUES('$title','$writer_id','$create_at','$updated_at')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else{
                    $conn->close();
                    return false;
                } 
            } 
        }
        
        //users allowed to load articles
        function userPermitted(){
            return array("14","28","1","43","44");
        }
        
        //check if article exist
        function articleExist($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $ids = mysqli_real_escape_string($conn,$id);
                $sql = "SELECT * FROM tbl_new_articles WHERE id='$ids'";
                $result= $conn->query($sql);
                if($result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        
        // get articles by id 
        function getArticle($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                
                $sql = "SELECT * FROM tbl_new_articles WHERE id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                    $a[] = $row;
                }
                $conn->close();
                return $a;
                }
            }
        }
        
        function getUserById($id) {
            $conn = new mysqli(DB_HOST3,DB_USER3,DB_PASS3,DB_NAME3);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_employee WHERE id = ".$id;
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = null;
                    while($row = $result->fetch_assoc()){
                        $aList = $row;
                    }
                    $conn->close();
                    return $aList;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        
        function isAllowed( $user_id, $type ){
            switch($type){
                case 'createArticle':
                    $roleIdsAllowed = array(1,4);
                    $res = $this->fetchRoleId($user_id);
                    if($res != 0){
                       if(in_array($res[0]['role_id'],$roleIdsAllowed)){
                            return true;
                        } else{
                            return false;
                        } 
                    } else{
                        return false;
                    }
                    break;
                
                case 'rewriteArticle':
                    $roleIdsAllowed = array(1,5,6);
                    $res = $this->fetchRoleId($user_id);
                    if($res != 0){
                       if(in_array($res[0]['role_id'],$roleIdsAllowed)){
                            return true;
                        } else{
                            return false;
                        } 
                    } else{
                        return false;
                    }
                    break;
                
                default:
                    return false;
                    break;
            }
        }
        
        function fetchRoleId($user_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE user_id='$user_id'";
                $result=$conn->query($sql);
                if($result->num_rows>0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return 0;
            }
        }
        
        function convert_smart_quotes($string)  {
            // $new_string = $this->htmlallentities($string);
            
            //handles Microsoft-encoded quotes (“ ” ‘ ’)
            $search = array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151)); 
            $replace = array("'", "'", '"', '"', '-', '-'); 
            return str_replace($search, $replace, $string); 
        }
        
        function htmlallentities($str){
            $res = '';
            $strlen = strlen($str);
            for($i=0; $i<$strlen; $i++){
                $byte = ord($str[$i]);
                if($byte < 128) // 1-byte char
                    $res .= $str[$i];
                elseif($byte < 192); // invalid utf8
                elseif($byte < 224) // 2-byte char
                    $res .= '&#'.((63&$byte)*64 + (63&ord($str[++$i]))).';';
                elseif($byte < 240) // 3-byte char
                    $res .= '&#'.((15&$byte)*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
                elseif($byte < 248) // 4-byte char
                    $res .= '&#'.((15&$byte)*262144 + (63&ord($str[++$i]))*4096 + (63&ord($str[++$i]))*64 + (63&ord($str[++$i]))).';';
            }
            return $res;
        }
        
        function addDelimeter($item){
            $item = rtrim($item) . " ";
            $needles = array('! ','? ','. ',"!\n","?\n",".\n");
            $lastPos = 0;
                        
            foreach($needles as $needle){
                while (($lastPos = strpos($item, $needle, $lastPos))!== false) {
                    $item = substr_replace($item, "[sentence-end]", ($lastPos+1), 0);
                    $lastPos = $lastPos + strlen($needle);
                    }
                }
            return trim($item);
        }
        
        function addDelimeter2($item){
            $item = rtrim($item) . " ";
            $needles = array('! ','? ','. ',"!\n","?\n",".\n");
            $lastPos = 0;
                        
            foreach($needles as $needle){
                while (($lastPos = strpos($item, $needle, $lastPos))!== false) {
                    $item = substr_replace($item, "<span style='color:red;'>[sentence-end]</span>", ($lastPos+1), 0);
                    $lastPos = $lastPos + strlen($needle);
                    }
                }
            return trim($item);
        }
        
        
        function applyArtConcluTag($val){
            $paragraphs="";
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $val) as $line){
              
                if($line == ""){
                    
                }else{
                    $length = strlen($line);
                    if($length <= 60){
                        $title = "[title]".$line."[title]";
                    }else{
                        $paragraphs .= "\n\n".$this->addDelimeter($line);
                    }
                
                }
                
            }
            $value = $title . $paragraphs;
            
            $value = trim($value);
            if($val != ""){
                $f = "[article-conclusion]\n\n";
                $f .= $value;
                $f .= "\n\n[article-conclusion]";
                return $f;
            }
        }
        
        function applyArtItemsTag($val){
            $paragraphs="";
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $val) as $line){
              
                if($line == ""){
                    
                }else{
                    $length = strlen($line);
                    if($length <= 60){
                        if($paragraphs != ""){
                            $items .= "\n\n". $title . $paragraphs;
                            $paragraphs = "";
                        }
                        $title = "[title]".$line."[title]";
                    }else{
                        $paragraphs .= "\n\n".$this->addDelimeter($line);
                    }
                
                }
                
            }
            if($paragraphs != ""){
                $items .= "\n\n".$title . $paragraphs;
                $paragraphs = "";
            }
            
            $value = $items;

            $value = trim($value);
            if($val != ""){
                $f = "[article-items]\n\n";
                $f .= $value;
                $f .= "\n\n[article-items]";
                return $f;
            }
        }
        
        function applyArtIntroTag($val){
            $paragraphs="";
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $val) as $line){
              
                if($line == ""){
                    
                }else{
                    $length = strlen($line);
                    if($length <= 60){
                        $title = "[title]".$line."[title]";
                    }else{
                        $paragraphs .= "\n\n".$this->addDelimeter($line);
                    }
                
                }
                
            }
            
            $value = $title . $paragraphs;
            
            
            $value = trim($value);
            if($val != ""){
                $f = "[article-intro]\n\n";
                $f .= $value;
                $f .= "\n\n[article-intro]";
                return $f;
            }
        }
        
        function applyTitleTag($val){
            if($val != ""){
                $f = "[title]";
                $f .= $val;
                $f .= "[title]";
                return $f;
            }
            
        }
        
        function applyArtTitleTag($val){
            if($val != ""){
                $f = "[article-title]";
                $f .= $val;
                $f .= "[article-title]";
                return $f;
            }
        }
        
        
        
        function getWordAiCredentials(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_credentials WHERE api='WordAI'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        
        function getPerfectTenseCred(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_credentials WHERE api='PerfectTense'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $res[] = $row;
                    }
                    $conn->close();
                    return $res;
                }
                $conn->close();
                return array();
            }
        }
        
        function deletePrintscreen($data){
            $conn = new mysqli(DB_HOST3,DB_USER3,DB_PASS3,DB_NAME3);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $date = mysqli_real_escape_string($conn,$data['date']);
                $users = $this->getMyUsers();
                foreach($users as $user){
                    
                    $sql = "SELECT * FROM tbl_printscreen WHERE userId=".$user['id']." AND newshotdate < '$date' ORDER BY newshotdate";
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $d[] = $row;
                        }
                        $conn->close();
                        return $d;
                    }
                    
                }
                
                $conn->close();
                return $d;
            }
        }
        
        // TABLE REWRITES FUNCTION //
        function fetchApprovedRewrites($sentence_id){
             $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                
                $sql = "SELECT * FROM tbl_rewrites WHERE sentence_id = '$sentence_id' AND status = '1'";
                $result = $conn->query($sql);
                if($result->num_rows > 0 ){
                    $conn->close();
                    return $result->num_rows;
                } else{
                    $conn->close();
                    return 0;
                }
            
            }
        }
        
        
        
    }
    
    
?>