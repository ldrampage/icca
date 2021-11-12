<?php 
    //save rewritten articles
    define("DB_HOSTA", "localhost");
    define("DB_USERA", "acllcmas_icca_user");
    define("DB_PASSA", "v@4K548#M{/;X[$<");
    define("DB_NAMEA", "acllcmas_icca");
    
    //get user info
    define("DB_HOSTH", "localhost");
    define("DB_USERH", "acllcmas_uhr01");
    define("DB_PASSH", '*wex(C{K"74f');
    define("DB_NAMEH", "acllcmas_mhr");
    
    class iccaFunc2 {
        // get all articles
        function getRewrittenArticles($cond = null){
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($cond)
                    $sql = "SELECT * FROM tbl_article_rewrites ".$cond;
                else 
                    $sql = "SELECT * FROM tbl_article_rewrites";
                    
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
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
        
        function getRewrittenArticle($id){
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_article_rewrites WHERE id = ".$id;  
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
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
        
        function getArticleById($id) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_articles WHERE _id = ".$id;
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
        
        function getUserById($id) {
            $conn = new mysqli(DB_HOSTH,DB_USERH,DB_PASSH,DB_NAMEH);
            
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
        
        function getEditors(){
            $conn = new mysqli(DB_HOSTH,DB_USERH,DB_PASSH,DB_NAMEH);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_employee WHERE position = 'Editor'";
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
        
        //for dropdown filter menus (rewritten articles page)
        function getRAEmployeeIds($flag) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if(!$flag) 
                    $sql = "SELECT * FROM tbl_article_rewrites";
                else 
                    $sql = "SELECT * FROM tbl_article_rewrites WHERE user_id = ".$_SESSION['login_id']." OR status = 1";
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
                    $aList = array();;
                    while($row = $result->fetch_assoc()){
                        if(!in_array($row['user_id'], $aList)) {
                            $aList[] = $row['user_id'];
                        }
                    }
                    $conn->close();
                    return $aList;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        function getRAArticleIds($flag) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if(!$flag) 
                    $sql = "SELECT * FROM tbl_article_rewrites";
                else 
                    $sql = "SELECT * FROM tbl_article_rewrites WHERE user_id = ".$_SESSION['login_id']." OR status = 1";
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
                    $aList = array();
                    while($row = $result->fetch_assoc()){
                        if(!in_array($row['article_id'], $aList)) {
                            $aList[] = $row['article_id'];
                        }
                    }
                    $conn->close();
                    return $aList;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        //update status (approve, reject rewrite)
        function updateStatus($id, $status, $reviewer) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "UPDATE tbl_article_rewrites SET status = ".$status.", reviewed_by = ".$reviewer." WHERE id = ".$id;
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
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
        
        // add new articles
        function addArticle($data){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else{
                
                $userPermitted = $this->userPermitted();
                if(in_array($data['creator'], $userPermitted)){
                    
                    $create_by = mysqli_real_escape_string($conn,$data['creator']);
                    $title = mysqli_real_escape_string($conn,$data['title']);
                    $create_at = date("Y-m-d H:i:s");
                    
                    $sql = "INSERT INTO tbl_articles(created_by, title, created_at) VALUES ('$create_by','$title','$create_at')";
                    if($conn->query($sql) == TRUE) {
                        $last_id = $conn->insert_id;
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
        
        function getOriginalIntro($aid) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_intro_parag WHERE article_id = ".$aid." ORDER BY _id";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = array();;
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
        
        function getRewrittenIntro($id) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_rewritten_intro WHERE rewrite_id = ".$id." ORDER BY _id";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = array();;
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
        
        function getOriginalSubH($aid) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_subheading WHERE article_id = ".$aid." ORDER BY _id";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = array();;
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
        
        function getRewrittenSubH($id) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_subheading WHERE _id = ".$id;
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
        
        function getOriginalSubC($id) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_subh_content WHERE subh_id = ".$id." ORDER BY _id";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = array();;
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
        
        function getRewrittenSubC($id) {
            $conn = new mysqli(DB_HOSTA,DB_USERA,DB_PASSA,DB_NAMEA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_subh_rewritten_content WHERE rewrite_id = ".$id." ORDER BY orig_subh_parag_id";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    $aList = array();;
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
        
        function insertIntroductionTitleAndParagraphs($data,$article_id,$type,$introTitle){
            
            print_r($data);
            
            
        }
        
        
        
    }


?>