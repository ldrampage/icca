<?php 

    //save rewritten articles
    define("DB_HOST2", "localhost");
    define("DB_USER2", "acllcmas_icca_user");
    define("DB_PASS2", "v@4K548#M{/;X[$<");
    define("DB_NAME2", "acllcmas_icca");
    
    class iccaFunc {
        function alreadyExists($aid, $uid) {
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_article_rewrites WHERE article_id = ".$aid." AND user_id = ".$uid;
                
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows == 1){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function insertRewrittenSubheading($sentences,$subHParagId,$subHId,$articId,$writerId){
            unset($sentences['savesubHParagraph']);
            //combine sentences.
            $paragraph = "";
            foreach($sentences as $item){
                $item = trim($item);
                //echo $item;
                if(substr($item, -1) != "."){
                    $item = $item .= ". ";
                } else{
                    $item = $item .= " ";
                }
                $paragraph .= $item;
            }
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $paragraph = mysqli_real_escape_string($conn,$paragraph);
                $subHParagId = mysqli_real_escape_string($conn,$subHParagId);
                $subHId = mysqli_real_escape_string($conn,$subHId);
                $writerId = mysqli_real_escape_string($conn,$writerId);
                $create_at = date("Y-m-d H:i:s");
                
                //check if already inserted to tbl_article_rewrites
                if(!$this->alreadyExists($articId, $writerId)) {
                    $last_id = null;
                    $sql = "INSERT INTO tbl_article_rewrites(article_id,user_id,created_at,last_edited_at) VALUES('$articId','$writerId','$create_at', '$create_at')";
                    if($conn->query($sql) == TRUE) {
                        $last_id = $conn->insert_id;
                    } else {
                        $conn->close();
                        return 'failed';
                    }
                    
                    if($last_id) {
                        $sql = "INSERT INTO tbl_subh_rewritten_content(orig_subh_parag_id,subh_id,writer_id,paragraph,created_at,rewrite_id) VALUES('$subHParagId','$subHId','$writerId','$paragraph','$create_at', '$last_id')";
                            if($conn->query($sql) == TRUE) {
                            $conn->close();
                            return true;
                        } else {
                            $conn->close();
                            return 'failed';
                        }
                    }
                } else {
                    $sql = "SELECT * FROM tbl_article_rewrites WHERE user_id = ".$writerId." AND article_id = ".$articId;  
                    $result = $conn->query($sql);
                    
                    $id = null;
                    if(!empty($result) && $result->num_rows == 1) {
                        while($row = $result->fetch_assoc()){
                            $id = $row['id'];
                        }
                    } else {
                        $conn->close();
                        return 'failed';
                    }
                    
                    if($id) {
                        $sql = "INSERT INTO tbl_subh_rewritten_content(orig_subh_parag_id,subh_id,writer_id,paragraph,created_at,rewrite_id) VALUES('$subHParagId','$subHId','$writerId','$paragraph','$create_at', '$id')";
                            if($conn->query($sql) == TRUE) {
                            $conn->close();
                            return true;
                        } else {
                            $conn->close();
                            return 'failed';
                        }
                    }
                }
            }
        }
        
        function getSubHParagraph($parag_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $parag_id = mysqli_real_escape_string($conn,$parag_id);
                $sql = "SELECT * FROM tbl_subh_content WHERE _id = '$parag_id'";
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
        
        function insertRewrittenIntro($sentences, $paragId, $articId, $writerId){
            //combine sentences
            unset($sentences['saveParagraph']);
            $paragraph = "";
            foreach($sentences as $item){
                $item = trim($item);
                //echo $item;
                if(substr($item, -1) != "."){
                    $item = $item .= ". ";
                } else{
                    $item = $item .= " ";
                }
                $paragraph .= $item;
            }
            
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $paragraph = mysqli_real_escape_string($conn,$paragraph);
                $paragId = mysqli_real_escape_string($conn,$paragId);
                $articId = mysqli_real_escape_string($conn,$articId);
                $writerId = mysqli_real_escape_string($conn,$writerId);
                $create_at = date("Y-m-d H:i:s");
                
                //check if already inserted to tbl_article_rewrites
                if(!$this->alreadyExists($articId, $writerId)) {
                    $last_id = null;
                    $sql = "INSERT INTO tbl_article_rewrites(article_id,user_id,created_at,last_edited_at) VALUES('$articId','$writerId','$create_at', '$create_at')";
                    if($conn->query($sql) == TRUE) {
                        $last_id = $conn->insert_id;
                    } else {
                        $conn->close();
                        return 'failed';
                    }
                    
                    if($last_id) {
                        $sql = "INSERT INTO tbl_rewritten_intro(orig_parag_id,rewrite_id,writer_id,paragraph,created_at) VALUES('$paragId','$last_id','$writerId','$paragraph','$create_at')";
                            if($conn->query($sql) == TRUE) {
                            $conn->close();
                            return true;
                        } else {
                            $conn->close();
                            return 'failed';
                        }
                    }
                } else {
                    $sql = "SELECT * FROM tbl_article_rewrites WHERE user_id = ".$writerId." AND article_id = ".$articId;  
                    $result = $conn->query($sql);
                    
                    $id = null;
                    if(!empty($result) && $result->num_rows == 1) {
                        while($row = $result->fetch_assoc()){
                            $id = $row['id'];
                        }
                    } else {
                        $conn->close();
                        return 'failed';
                    }
                    
                    if($id) {
                        $sql = "INSERT INTO tbl_rewritten_intro(orig_parag_id,rewrite_id,writer_id,paragraph,created_at) VALUES('$paragId','$id','$writerId','$paragraph','$create_at')";
                            if($conn->query($sql) == TRUE) {
                            $conn->close();
                            return true;
                        } else {
                            $conn->close();
                            return 'failed';
                        }
                    }
                }
            }
            
        }
        
        function getArticleIntroParagraph($orig_id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $id = mysqli_real_escape_string($conn,$orig_id);
                $sql= "SELECT * FROM tbl_intro_parag WHERE _id='$id'";
                $result=$conn->query($sql);
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
        
        function getSubHContent($ids){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $id = mysqli_real_escape_string($conn,$ids);
                $sql = "SELECT * FROM tbl_subh_content WHERE subh_id='$id'";
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
        
        function getSubHeadings($ids){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                
                $id = mysqli_real_escape_string($conn, $ids);
                $sql = "SELECT * FROM tbl_subheading WHERE article_id='$id'";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $subs = array();
                    while($row = $result->fetch_assoc()){
                        $subs[] = $row;
                    }
                    $conn->close();
                    return $subs;
                } else{
                    $conn->close();
                    return array();
                }
                
            }
        }
        
        function insertSubAndContent($data,$id,$subheading_title){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                
                unset($data['formS']);
                unset($data['subheading_title']);
                unset($data['article_id']);
                
                $article_id = mysqli_real_escape_string($conn,$id);
                $subheading_t = mysqli_real_escape_string($conn,$subheading_title);
                $create_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_subheading(article_id, subheading, created_at) VALUES('$article_id','$subheading_t','$create_at')";
                if($conn->query($sql) == TRUE) {
                    $last_id = $conn->insert_id;
                    foreach($data as $item){
                        $create_at = date("Y-m-d H:i:s");
                        $it = mysqli_real_escape_string($conn,$item);
                        $sql2 = "INSERT INTO tbl_subh_content(subh_id, content,created_at) VALUES('$last_id','$it','$create_at')";
                        $result = $conn->query($sql2);
                    }
                    if($result){
                        $conn->close();
                        return true;
                    } else{
                        $conn->close();
                        return false;
                    }
                    
                }
            }
        }
        
        function getArticleIntro($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $sql = "SELECT * FROM tbl_intro_parag WHERE article_id='$id'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $arr = array();
                    while($row = $result->fetch_assoc()){
                        $arr[] = $row;
                    }
                    $conn->close();
                    return $arr;
                }
                $conn->close();
                return array();
            }
        }
    
        function insertIntroPar($data,$id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                $article_id = mysqli_real_escape_string($conn,$id);
                //echo $article_id;
                
                unset($data['article_id']);
                unset($data['formP']);
                foreach($data as $item){
                    $created_at = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO tbl_intro_parag(article_id, intro_paragraph, created_at) VALUES('$article_id','$item','$created_at')";
                    if($conn->query($sql) == TRUE) {
                        $result = TRUE;
                    } else{
                        $result = FALSE;
                    }

                }
                $conn->close();
                return $result; 
                
            }
        }
        
        // get articles by id 
        function getArticle($id){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                
                $sql = "SELECT * FROM tbl_articles WHERE _id='$id'";
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
        
        
        
        // get all articles
        function getAllArticles(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
            
                $sql = "SELECT * FROM tbl_articles";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = array();
                    while($row = $result->fetch_assoc()){
                        $aList[] = $row;
                    }
                    $conn->close();
                    return $aList;
                } else{
                    $conn->close();
                    return array();
                }
                
            }
        }
        
        
        // add new articles
        function addArticle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
            } else{
                
                $userPermitted = $this->userPermitted();
                if(in_array($data['creator'], $userPermitted)){
                    
                    $create_by = mysqli_real_escape_string($conn,$data['creator']);
                    $title = mysqli_real_escape_string($conn,$data['title']);
                    $create_at = date("Y-m-d H:i:s");
                    
                    $sql = "INSERT INTO tbl_articles(created_by, title, created_at) VALUES ('$create_by','$title','$create_at')";
                    if($conn->query($sql) == TRUE) {
                        $last_id = $conn->insert_id;
                        $conn->close();
                        return $last_id;
                    } else{
                        $conn->close();
                        return false;
                    }
                    
                } else{
                    $conn->close();
                    return "invalid_user";
                }
                
                
            } 
            $conn->close();
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
                $sql = "SELECT * FROM tbl_articles WHERE _id='$ids'";
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
        
        
        
    }


?>