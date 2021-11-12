<?php 
    //save rewritten articles
    define("DB_HOSTAA", "localhost");
    define("DB_USERAA", "acllcmas_icca_user");
    define("DB_PASSAA", "v@4K548#M{/;X[$<");
    define("DB_NAMEAA", "acllcmas_icca");
    
    define("DB_HOSTHH", "68.66.214.205");
    define("DB_USERHH", "backoff3_tmuhr");
    define("DB_PASSHH", "sTmwbw4a0Ylp");
    define("DB_NAMEHH", "backoff3_mhr"); 
    
    /*
    define("DB_HOSTHH", "localhost");
    define("DB_USERHH", "acllcmas_nmuhr");
    define("DB_PASSHH", "_kc?~Ew[%5,z");
    define("DB_NAMEHH", "acllcmas_mhr"); */
    
    date_default_timezone_set('Asia/Manila');
    
    class iccaFunc2New {
        function getArticleCategories($cond = '') {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_category".$cond;
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
        
        function assignCategory($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "UPDATE tbl_new_articles SET category_id = ".$data['category_id']." WHERE id = ".$data['article_id'];
                if($conn->query($sql) == TRUE) {
                    # insert log
                    $user = $this->getUserById($data['user_id']);
                    $action = trim($user['fname'])." ".trim($user['lname'])." assigned a category to the following article";
                    $from_ = $data['article_title'];
                    $log = $this->addArticleLog($data['article_id'], $action, $from_, '');
                    
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function isRewriteOpen($id) {
           $rewrite = $this->getRewrittenById(intval($id));
           if(!empty($rewrite) && $rewrite['is_open']) {
               return true;
           }
           return false;
        }
        
        function getEditTime($id) {
           $rewrite = $this->getRewrittenById(intval($id));
           if(!empty($rewrite)) {
               return $rewrite['last_edit_time'];
           }
           return null;
        }
        
        function setRewriteOpen($id, $open) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $updated_at = date("Y-m-d H:i:s");
                $is_open = mysqli_real_escape_string($conn,$open);
                
                $sql = "UPDATE tbl_rewrites SET is_open= '".$is_open."', last_edit_time = '".$updated_at."' WHERE id = ".intval($id);
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }    
        }
        
        function setExportStatus($aid, $status) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $export_status = mysqli_real_escape_string($conn,$status);
                
                $sql = "UPDATE tbl_new_articles SET ready_for_export = '".$export_status."' WHERE id = ".intval($aid);
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }    
        }
        
        function updateExportStatus($rewrite_id) {
            $rewrite = $this->getRewrittenById($rewrite_id);
				
            # recheck export status
            $export_status = $this->checkExportAvailability($rewrite['article_id']);
            $res_export_status = $this->setExportStatus($rewrite['article_id'], $export_status['value']);
            
            //set rewrite_progress
            if($res_export_status) {
                $rewrite_stats = $this->getArticleRewritesTotal($rewrite['article_id']);
                $percentage = ($rewrite_stats['count'] / $rewrite_stats['total']) * 100;
                $this->setArticleTotalRewrites($rewrite['article_id'], round($percentage));    
            }
        }
        
        function updateExportStatusBySentence($sentence_id) {
            $sentence = $this->getSentenceById($sentence_id);
				
            # recheck export status
            $export_status = $this->checkExportAvailability($sentence['article_id']);
            $res_export_status = $this->setExportStatus($sentence['article_id'], $export_status['value']);
            
            //set rewrite_progress
            if($res_export_status) {
                $rewrite_stats = $this->getArticleRewritesTotal($sentence['article_id']);
                $percentage = ($rewrite_stats['count'] / $rewrite_stats['total']) * 100;
                $this->setArticleTotalRewrites($sentence['article_id'], round($percentage));    
            }
        }
        
        function updateExportStatusByArticle($article_id) {
            # recheck export status
            $export_status = $this->checkExportAvailability($article_id);
            $res_export_status = $this->setExportStatus($article_id, $export_status['value']);
            
            //set rewrite_progress
            if($res_export_status) {
                $rewrite_stats = $this->getArticleRewritesTotal($article_id);
                $percentage = ($rewrite_stats['count'] / $rewrite_stats['total']) * 100;
                $this->setArticleTotalRewrites($article_id, round($percentage));    
            }
        }
        
        function setRewriteProgressAll(){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_new_articles WHERE status = '1' AND rewrite_progress = '0'";
                $result = $conn->query($sql);
                echo $result->num_rows;
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $rewrite_stats = $this->getArticleRewritesTotal($row['id']);
                        $percentage = ($rewrite_stats['count'] / $rewrite_stats['total']) * 100;
                        $this->setArticleTotalRewrites($row['id'], round($percentage));
                        echo $row['id'] . " DONE";
                    }
                    $conn->close();
                } else {
                    $conn->close();
                }
            }
        }
        
        function updateEditTime($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $updated_at = date("Y-m-d H:i:s");
                
                $sql = "UPDATE tbl_rewrites SET last_edit_time = '".$updated_at."' WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }    
        }
        
        //WordAI
        function processSpin($data) {
            $creds = $this->getCredentials("WHERE api = 'WordAI'");
            $creds = $creds[0];
            
            $email = $creds['username'];
            $password = $creds['api_key'];
            $quality = "Very Readable";
            $text = $data['sentence'];
            
            return $this->apiWordAI($text,$quality,$email,$password);
        }
        
        function apiWordAI($text,$quality,$email,$pass) {
            if(isset($text) && isset($quality) && isset($email) && isset($pass)) {
                $text = urlencode($text);
                $ch = curl_init('http://wordai.com/users/turing-api.php');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_POST, 1);
                curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&returnspin=true&nooriginal=on&perfect_tense=correct&output=json");
                $result = curl_exec($ch);
                curl_close ($ch);
        
                return $result;
            } else {
                return 'Error: Not All Variables Set!';
            }
        }
        
        function getCredentials($cond = null){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error) {
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_credentials ".$cond;
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0) {
                    $aList = array();
                    while($row = $result->fetch_assoc()) {
                        $aList[] = $row;
                    }
                    $conn->close();
                    return $aList;
                } else {
                    $conn->close();
                    return null;
                }
            }
        }
        
        function exportArticle($data) {
            $result = array(
                "status" => "",
                "article_title" => "",
                "original" => "",
                "rewrite" => "",
                "availability" => "",
                "article_id" => "",
                "link" => "#"
            );
            
            // if(!$data['is_available']) {
            //     $result['status'] = "invalid";
            //     return $result;
            // } 
            
            $orig_article_content = "";
            $rewrite_article_content = "";
            
            //get contents of article (tbl_contents)
            $contents = $this->getContents1('WHERE article_id = '.$data['sel_article'].' ORDER BY FIELD(type, "introduction", "subheading", "conclusion")');
            $subh_ids = array();
            $subh_titles = array();
            
            //CHECK IF THIS ARTICLE has an intro, at least 1 subheading, and a conclusion
            $hasIntro = false;
            $hasSubheading = false;
            $hasConclusion = false;
            foreach($contents as $content) {
                if($content['type'] == 'introduction')  $hasIntro = true;
                else if($content['type'] == 'subheading')  $hasSubheading = true;
                else if($content['type'] == 'conclusion')  $hasConclusion = true;
            }
            if(!$hasIntro || !$hasSubheading || !$hasConclusion) {
                $result['status'] = "incomplete";
                return $result;
            }
            
            //get sentences of article (intro, subh, conclusion)
            $intro_sentences = $this->getSentences('WHERE article_id = '.$data['sel_article'].' AND content_id = '.$contents[0]['id']. ' ORDER BY paragraph_no, order_no');
            
            //gets ids and titles of content subheadings (tbl_contents)
            for($i=1;$i<sizeof($contents)-1;$i++) {
                $subh_ids[] = $contents[$i]['id'];
                $subh_titles[] = $contents[$i]['title'];
            }
            $subh_sentences = $this->getSentences('WHERE article_id = '.$data['sel_article'].' AND content_id IN ('.implode(",", $subh_ids). ') ORDER BY content_id, paragraph_no, order_no');
            $concl_sentences = $this->getSentences('WHERE article_id = '.$data['sel_article'].' AND content_id = '.end($contents)['id']. ' ORDER BY paragraph_no, order_no');
            
            //get 1 APPROVE rewrite for each sentence
            $intro_rewrites = array();
            foreach($intro_sentences as $sent) {
                $rewrite = $this->getRewrittenArticles('WHERE sentence_id = '.$sent['id'].' AND status = 1 ORDER BY RAND() LIMIT 1'); 
                $intro_rewrites[] = $rewrite[0];
            }
            
            $subh_rewrites = array();
            foreach($subh_sentences as $sent) {
                $rewrite = $this->getRewrittenArticles('WHERE sentence_id = '.$sent['id'].' AND status = 1 ORDER BY RAND() LIMIT 1'); 
                $subh_rewrites[] = $rewrite[0];
            }
            
            $concl_rewrites = array();
            foreach($concl_sentences as $sent) {
                $rewrite = $this->getRewrittenArticles('WHERE sentence_id = '.$sent['id'].' AND status = 1 ORDER BY RAND() LIMIT 1'); 
                $concl_rewrites[] = $rewrite[0];
            }
            
            //set filename
            $article = $this->getArticleById($data['sel_article']);
            $title = trim(preg_replace('!\s+!', ' ', $article['title']));
            $filename = preg_replace("/[^a-zA-Z0-9]/", "_", $title);
            $filename = "downloads/rewrite__".$filename."__".date('ymd').".txt";
            
            //prepare txt file for exporting rewrites
            $txt = fopen($filename, "w") or die("Unable to open file!");
            
            //insert intro rewrites
            if($contents[0]['title'] && $contents[0]['title'] != 'none' && $contents[0]['title'] != '') {
                fwrite($txt, $contents[0]['title']."\n\n");  
                $rewrite_article_content .= $contents[0]['title']."\n\n";
                $orig_article_content .= $contents[0]['title']."\n\n";
            }
            $curr_par = $intro_rewrites[0]['paragraph_no'];
            $counter = 0;
            foreach($intro_rewrites as $sent) {
                fwrite($txt, $sent['sentence']." ");
                $rewrite_article_content .= $sent['sentence']." ";
                $orig_article_content .= $intro_sentences[$counter++]['sentence']." ";
                if($curr_par != $sent['paragraph_no']) {
                    fwrite($txt, "\n\n");
                    $rewrite_article_content .= "\n\n";
                    $orig_article_content .= "\n\n";
                    $curr_par = $sent['paragraph_no'];
                }
            }
            fwrite($txt, "\n\n");
            $rewrite_article_content .= "\n\n";
            $orig_article_content .= "\n\n";
            
            //insert subh rewrites
            fwrite($txt, $contents[1]['title']."\n\n");
            $rewrite_article_content .= $contents[1]['title']."\n\n";
            $orig_article_content .= $contents[1]['title']."\n\n";
            $curr_par = $subh_rewrites[0]['paragraph_no'];
            $curr_subh = $this->getSentenceById($subh_rewrites[0]['sentence_id']);
            $subheading = $curr_subh['content_id'];
            $count = 1;
            $counter = 0;
            foreach($subh_rewrites as $sent) {
                $curr_subh = $this->getSentenceById($sent['sentence_id']);
                if($curr_subh['content_id'] != $subheading) {
                    fwrite($txt, "\n\n".$contents[++$count]['title']."\n\n");
                    $rewrite_article_content .= "\n\n".$contents[$count]['title']."\n\n";
                    $orig_article_content .= "\n\n".$contents[$count]['title']."\n\n";
                    fwrite($txt, $sent['sentence']." ");
                    $rewrite_article_content .= $sent['sentence']." ";
                    $orig_article_content .= $subh_sentences[$counter++]['sentence']." ";
                    $subheading = $curr_subh['content_id'];
                    $curr_par = $curr_subh['paragraph_no'];
                } else {
                    fwrite($txt, $sent['sentence']." ");
                    $rewrite_article_content .= $sent['sentence']." ";
                    $orig_article_content .= $subh_sentences[$counter++]['sentence']." ";
                }
                
                if($curr_par != $sent['paragraph_no']) {
                    fwrite($txt, "\n\n");
                    $rewrite_article_content .= "\n\n";
                    $orig_article_content .= "\n\n";
                    $curr_par = $sent['paragraph_no'];
                }
            }
            fwrite($txt, "\n\n");
            $rewrite_article_content .= "\n\n";
            $orig_article_content .= "\n\n";
            
            //insert conclusion rewrites
            if(end($contents)['title'] && end($contents)['title'] != 'none' && end($contents)['title'] != '') {
                fwrite($txt, end($contents)['title']."\n\n");  
                $rewrite_article_content .= end($contents)['title']."\n\n";
                $orig_article_content .= end($contents)['title']."\n\n";
            }
            $curr_par = $concl_rewrites[0]['paragraph_no'];
            $counter = 0;
            foreach($concl_rewrites as $sent) {
                fwrite($txt, $sent['sentence']." ");
                $rewrite_article_content .= $sent['sentence']." ";
                $orig_article_content .= $concl_sentences[$counter++]['sentence']." ";
                if($curr_par != $sent['paragraph_no']) {
                    fwrite($txt, "\n\n");
                    $rewrite_article_content .= "\n\n";
                    $orig_article_content .= "\n\n";
                    $curr_par = $sent['paragraph_no'];
                }
            }
            
            fclose($txt);
            
            $result['status'] = "success";
            $result['link'] = $filename;
            $result['article_title'] = $article['title'];
            $result['rewrite'] = $rewrite_article_content;
            $result['original'] = $orig_article_content;
            $result['article_id'] = $data['sel_article'];
            $result['category_id'] = $data['sel_category'];
            // $result['availability'] = $data['is_available'];
            return $result;
        }
        
        function addLog($id, $action, $from_, $to_){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $action_str = mysqli_real_escape_string($conn,$action);
                $from_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $from_)));
                $to_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $to_)));
                $created_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_logs(rewrite_id,action,from_,to_,created_at)VALUES('$id','$action_str','$from_str','$to_str','$created_at')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function addArticleLog($id, $action, $from_, $to_){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $action_str = mysqli_real_escape_string($conn,$action);
                $from_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $from_)));
                $to_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $to_)));
                $created_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_logs(article_id,action,from_,to_,created_at)VALUES('$id','$action_str','$from_str','$to_str','$created_at')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function addRewriteLog($sid, $id, $action, $from_, $to_){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $action_str = mysqli_real_escape_string($conn,$action);
                $from_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $from_)));
                $to_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $to_)));
                $created_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_logs(sentence_id,rewrite_id,action,from_,to_,created_at)VALUES('$sid', '$id','$action_str','$from_str','$to_str','$created_at')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function addSentLog($aid, $id, $action, $from_, $to_){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $action_str = mysqli_real_escape_string($conn,$action);
                $from_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $from_)));
                $to_str = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $to_)));
                $created_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_logs(article_id,sentence_id,action,from_,to_,created_at)VALUES('$aid', '$id','$action_str','$from_str','$to_str','$created_at')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function getSentLogs($aid){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_logs WHERE article_id = ".$aid." ORDER BY created_at DESC";
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
        
        function addNote($data){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $rewrite_id = $data['rewrite_id'];
                $user_id = $data['user_id'];
                $note = mysqli_real_escape_string($conn,$data['new_note']);
                $created_at = date("Y-m-d H:i:s");
                
                $sql = "INSERT INTO tbl_notes(rewrite_id,note,noted_by,created_at,updated_at)VALUES('$rewrite_id','$note','$user_id','$created_at','$created_at')";
                if($conn->query($sql) == TRUE){
                    # insert log
                    $user = $this->getUserById($user_id);
                    $action = trim($user['fname'])." ".trim($user['lname'])." added a note to this rewrite";
                    $from_ = $data['new_note'];
                    $log = $this->addLog($rewrite_id, $action, $from_, '');
                    
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function editNote($data){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $id = $data['note_id'];
                $note = mysqli_real_escape_string($conn,$data['u_new_note']);
                $updated_at = date("Y-m-d H:i:s");
                
                $sql = "UPDATE tbl_notes SET note = '".$note."', updated_at = '".$updated_at."' WHERE id = ".$id;
                if($conn->query($sql) == TRUE){
                    # insert log
                    $user = $this->getUserById($data['u_user_id']);
                    $action = trim($user['fname'])." ".trim($user['lname'])." updated their note on this rewrite";
                    $from_ = $data['u_old_note'];
                    $to_ = $data['u_new_note'];
                    $log = $this->addLog($data['u_rewrite_id'], $action, $from_, $to_);
                    
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function deleteNote($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "DELETE FROM tbl_notes WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function editCategoryOfRewrite($cid, $rewrite_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "UPDATE tbl_rewrites SET category_id = '".$cid."' WHERE id = ".$rewrite_id;
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function getContents($aid){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_contents WHERE article_id = ".$aid." ORDER BY type, title";
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
        
        function getContents1($cond){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_contents ".$cond;
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
        
        function getSentences($cond){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_sentences ".$cond;
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
        
        function setArticleTotalRewrites($id,$percentage){
             $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                
                $sql = "UPDATE tbl_new_articles SET rewrite_progress = '".$percentage."' WHERE id = $id";
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function getArticleRewritesTotal($id) {
            $sentences = iccaFunc2New::getSentences('WHERE article_id = '.$id);
            $count = 0;
            $total = count($sentences);
            
            if(!empty($sentences)) {
                foreach($sentences as $sent) {
                    $approve_exists = iccaFunc2New::isRewriteAvailable($sent['id']);
                    if($approve_exists) {
                        $count++;
                    }
                }  
            }
            
            return array(
                "count" => $count,
                "total" => $total
            );
        }
        
        function getSentenceWithoutApproveRewrite($id){
            $sentences = $this->getSentences('WHERE article_id = '.$id);
            $total = count($sentences);
            if(!empty($sentences)) {
                foreach($sentences as $sent) {
                    $rs = $this->isRewriteAvailable2($sent['id']);
                    if(!$rs) {
                        $noApproveRewrite[] = $sent['id'];
                    }
                }
                return $noApproveRewrite;
            }
            return null;
        }
        
        function isRewriteAvailable2($sentence_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error); return false;
            } else {
                $sql = "SELECT * FROM tbl_rewrites WHERE sentence_id = ".$sentence_id." AND status = 1";  
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0) {
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function getRewriteProgress($article_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error); return false;
            } else {
                $sql = "SELECT rewrite_progress FROM tbl_new_articles WHERE id='$article_id'";
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $conn->close();
                    return $row['rewrite_progress'];
                } else {
                    $conn->close();
                }
            }
        }
        
        
        function getSentenceIds($cond){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_sentences ".$cond;
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
                    $aList = array();
                    while($row = $result->fetch_assoc()){
                        $aList[] = $row['id'];
                    }
                    $conn->close();
                    return $aList;
                } else{
                    $conn->close();
                    return array();
                }
            }
        }
        
        //get unique rewrites
        function getUniqueRewrites($cond = null){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($cond)
                    $sql = "SELECT * FROM tbl_rewrites ".$cond." GROUP BY user_id, article_id";
                else 
                    $sql = "SELECT * FROM tbl_rewrites GROUP BY user_id, article_id";
                    
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
        
        // get all articles
        function getRewrittenArticles($cond = null){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($cond)
                    $sql = "SELECT * FROM tbl_rewrites ".$cond;
                else 
                    $sql = "SELECT * FROM tbl_rewrites";
                    
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
        
        function getRewrittenSentences($uid, $aid, $sql_stmt){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_rewrites WHERE user_id = ".$uid." AND article_id = ".$aid.$sql_stmt;
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
        
        function getNotes($rewrite_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_notes WHERE rewrite_id = ".$rewrite_id;
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
        
        function getLogs($rewrite_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_logs WHERE rewrite_id = ".$rewrite_id." ORDER BY created_at DESC";
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
        
        function getAssignmentUsers() {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments GROUP BY user_id ORDER BY user_id";
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
                    $aList = array();
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
        
        function getRoles(){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_roles ORDER BY role";
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
        
        function getRoleById($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_roles WHERE id = ".$id;
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
        
        function assignExist($user_id, $role_id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE user_id = ".$user_id." AND role_id = ".$role_id;
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function addAssignment($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                if($this->assignExist($data['c_user_id'], $data['role_id'])) {
                    $conn->close();
                    return array("This assignment already exists!");    
                } 
                
                $sql = "INSERT INTO tbl_assignments(user_id,role_id)VALUES('".$data['c_user_id']."','".$data['role_id']."')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function updateAssignment($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                if($data['current_role'] != $data['role'] && $this->assignExist($data['u_user_id'], $data['role'])) {
                    $conn->close();
                    return array("This assignment already exists!");    
                } 
                
                $sql = "UPDATE tbl_assignments SET role_id = ".$data['role']." WHERE id = ".$data['assign_id'];
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function deleteAssignment($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "DELETE FROM tbl_assignments WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function getAssignments($cond = null){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($cond) {
                    $sql = "SELECT * FROM tbl_assignments ".$cond;    
                } else {
                    $sql = "SELECT * FROM tbl_assignments";
                }
                
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
        
        function addCredential($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                if($this->credExist($data['add_api'])) {
                    $conn->close();
                    return array("This credential already exists!");    
                } 
                
                $api = mysqli_real_escape_string($conn,trim($data['add_api']));
                $user = mysqli_real_escape_string($conn,trim($data['add_user']));
                $key = mysqli_real_escape_string($conn,trim($data['add_key']));
                
                $sql = "INSERT INTO tbl_credentials(api,username,api_key)VALUES('".$api."','".$user."','".$key."')";
                if($conn->query($sql) == TRUE){
                    $last_id = $conn->insert_id;
                    $conn->close();
                    return $last_id;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function credExist($api_name) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $sql = "SELECT * FROM tbl_credentials WHERE api = '".$api_name."'";
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function updateCredential($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
                return false;
            } else {
                $user = mysqli_real_escape_string($conn,trim($data['edit_user']));
                $key = mysqli_real_escape_string($conn,trim($data['edit_key']));
                
                $sql = "UPDATE tbl_credentials SET username = '".$user."', api_key = '".$key."' WHERE id = ".$data['cred_id'];
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function getRewrittenById($id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_rewrites WHERE id = ".$id;  
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
        
        function isRewriteAvailable($sentence_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error); return false;
            } else {
                $sql = "SELECT * FROM tbl_rewrites WHERE sentence_id = ".$sentence_id." AND status = 1";  
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0) {
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function checkExportAvailability($aid) {
            $sent_ids = $this->getSentenceIds('WHERE article_id = '.$aid);
            $not_available_flag = false;
            
            if(!empty($sent_ids)) {
                foreach($sent_ids as $id) {
                    $rewrites = $this->getRewrittenArticles('WHERE sentence_id = '.$id.' AND status = 1');
                    if(empty($rewrites)) {
                        $not_available_flag = true;
                        break;
                    }
                }  
            } else {
               $not_available_flag = true; 
            }
            
            return array(
                "message" => $not_available_flag ? "<span class='text-red text-bold'>NO</span>" : "<span class='text-green text-bold'>YES</span>",
                "value" => $not_available_flag ? 0 : 1
            );
        }
        
        function getArticles($cond = '') {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_new_articles".$cond;
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
        
        function getArticleById($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_new_articles WHERE id = ".$id;
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
        
        function getSentenceById($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_sentences WHERE id = ".$id;
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
        
        function getContentById($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_contents WHERE id = ".$id;
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
        
        function getAllUsers() {
            $conn = new mysqli(DB_HOSTHH,DB_USERHH,DB_PASSHH,DB_NAMEHH);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_employee WHERE status = 1 ORDER BY fname";
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
        
        function getUserById($id) {
            $conn = new mysqli(DB_HOSTHH,DB_USERHH,DB_PASSHH,DB_NAMEHH);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_employee WHERE id = ".$id;
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
        
        function getDeptById($id) {
            $conn = new mysqli(DB_HOSTHH,DB_USERHH,DB_PASSHH,DB_NAMEHH);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_department WHERE id = ".$id;
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
        
        function getEditors(){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE role_id = 3";
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
        
        //for dropdown filter menus (rewritten articles page)
        function getRAEmployeeIds() {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_rewrites GROUP BY user_id, article_id";
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
        
        function getRAArticleIds() {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_rewrites GROUP BY user_id, article_id";
                $result = $conn->query($sql);
                
                if(!empty($result) && $result->num_rows > 0){
                    $aList = array();
                    while($row = $result->fetch_assoc()){
                        if(!in_array($row['article_id'], $aList)) {
                            $article = $this->getArticleById($row['article_id']);
                            if(intval($article['status'])) {
                                $aList[] = $row['article_id'];    
                            }
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
        function updateStatus($id, $status, $reviewer, $old_status) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                if($reviewer != 0) {
                    $sql = "UPDATE tbl_rewrites SET status = ".$status.", reviewed_by = ".$reviewer.", is_reject_rewrite = 0 WHERE id = ".$id;
                } else {
                    $sql = "UPDATE tbl_rewrites SET status = ".$status.", reviewed_by = ".$_SESSION['login_id'].", is_reject_rewrite = 0 WHERE id = ".$id;
                }
                
                if($conn->query($sql) == TRUE) {
                    # recheck export status of article associate with this rewrite
                    $export_status = $this->updateExportStatus($id);
                    
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function updateStatus1($id, $status) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "UPDATE tbl_rewrites SET status = ".$status.", is_reject_rewrite = 0 WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        /**** FOR DASHBOARD ****/
        
        //fetch the number of pending, approved, and rejected
        function fetchRewriteStatusCounts() {
            $result = array(
                "allCount" => 0,
                "pending" => 0,
                "approved" => 0,
                "rejected" => 0,
                "perPending" => 0,
                "perApproved" => 0,
                "perRejected" => 0
            );
            
            $allCount = 0;
            $pending = 0;
            $approved = 0;
            $rejected = 0;
            
            $rewrites = $this->getRewrittenArticles(); 
            foreach($rewrites as $rewrite) {
                //ADD CATEGORY
                    if($rewrite['category_id'] == 0) {
                        $article = $this->getArticleById($rewrite['article_id']);
                        if($article['category_id'] != 0) {
                            $res = $this->editCategoryOfRewrite($article['category_id'], $rewrite['id']);
                        }
                    }
                //ADD CATEGORY
                
                // if(intval($article['status'])) {
                    $allCount++;
                    
                    if($rewrite['status'] == 0) $pending++;
                    else if($rewrite['status'] == 1) $approved++;
                    else if($rewrite['status'] == 2 || $rewrite['status'] == 3) $rejected++;
                // }
            }
            
            $perPending = ($pending / $allCount) * 100;
            $perApproved = ($approved / $allCount) * 100;
            $perRejected = ($rejected / $allCount) * 100;
            
            $result['allCount'] = $allCount;
            $result['pending'] = $pending;
            $result['approved'] = $approved;
            $result['rejected'] = $rejected;
            $result['perPending'] = number_format($perPending,2);
            $result['perApproved'] = number_format($perApproved,2);
            $result['perRejected'] = number_format($perRejected,2);
            
            return $result;
        }
        
        function fetchArticleExportStatusCounts() {
            $result = array(
                "articlesCount" => 0,
                "articles_pending" => 0,
                "articles_completed" => 0,
            );
            
            $articlesCount = 0;
            $articles_pending = 0;
            $articles_completed = 0; 
            
            $articles = $this->getArticles(' WHERE status = 1');
            foreach($articles as $article) {
                $articlesCount++;
                
                // $res = $this->checkExportAvailability($article['id']);
                // if($res['value']) {
                //     $articles_completed++;
                // } else {
                //     $articles_pending++;
                // }
                
                if(intval($article['ready_for_export'])) {
                    $articles_completed++;
                } else {
                    $articles_pending++;
                }
            }
            
            $result['articlesCount'] = $articlesCount;
            $result['articles_pending'] = $articles_pending;
            $result['articles_completed'] = $articles_completed;
            return $result;
        }
        /**** FOR DASHBOARD (end) ****/
        
        function updateRewrite($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            $sentence = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $data['new_sentence'])));
            $updated_at = date("Y-m-d H:i:s");
            
            $messages = array_filter(explode(".", $data['err_msg']));
            if(!empty($messages)) {
                return $messages;
            }
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "UPDATE tbl_rewrites SET sentence = '".$sentence."', updated_at = '".$updated_at."', status = '".$data['update_status']."', is_open = '1' WHERE id = ".$data['rewrite_id'];
                if($conn->query($sql) == TRUE) {
                    # insert to log if changes occur
                    $user = $this->getUserById($_SESSION['login_id']);
                    
                    if($data['orig_sentence2'] != $data['new_sentence']) {
                        $action = trim($user['fname'])." ".trim($user['lname'])." changed this rewrite";
                        $from_ = $data['orig_sentence2'];
                        $to_ = $data['new_sentence'];
                        $log = $this->addLog($data['rewrite_id'], $action, $from_, $to_);
                    }
                    
                    # check if status is updated
                    if($data['old_status'] != $data['update_status']) {
                        $sql = "UPDATE tbl_rewrites SET reviewed_by = '".$user['id']."', is_reject_rewrite = 0 WHERE id = ".$data['rewrite_id'];
                        if(intval($data['update_status']) == 1 || intval($data['update_status']) == 2) {
                            $res = $conn->query($sql);
                            
                            $statuses = array("PENDING", "APPROVED", "REJECTED", "REJECTED & REWRITTEN");
                            $action = trim($user['fname'])." ".trim($user['lname'])." set the status of this rewrite";
                            $from_ = $statuses[intval($data['old_status'])];
                            $to_ = $statuses[intval($data['update_status'])];
                            $log = $this->addLog($data['rewrite_id'], $action, $from_, $to_);
                            $export_status = $this->updateExportStatus($data['rewrite_id']);
                        }
                    }
                    
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function deleteRewrite($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "DELETE FROM tbl_rewrites WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    //delete notes associate with this rewrite
                    $sql = "DELETE FROM tbl_notes WHERE rewrite_id = ".$id;
                    if($conn->query($sql) == TRUE) {
                        $conn->close();
                        return true;
                    }
                    
                    //delete logs associate with this rewrite
                    $sql = "DELETE FROM tbl_logs WHERE rewrite_id = ".$id;
                    if($conn->query($sql) == TRUE) {
                        $conn->close();
                        return true;
                    }
                    
                    # recheck export status of article associate with this rewrite
                    $export_status = $this->updateExportStatus($id);
                    
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function addSentence($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            $sentence = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $data['new_sentence_add'])));
            $user = $this->getUserById($_SESSION['login_id']);
            
            //collect data
            $article = $data['sel_article'];
            $content = $data['sel_content'];
            $paragraph = $data['sel_paragraph'];
            
            //get the new order for the created sentence
            $position = $data['sel_position'];
            $sentence_position = $data['sel_sentence_pos'];
            
            if($position == 'before') {
                $order = intval($sentence_position);
            } else {
                $order = intval($sentence_position) + 1;
            }
            
            $isLastSentence = false;
            $par_sentences = $this->getSentences('WHERE content_id = '.$content.' AND paragraph_no = '.$paragraph.' ORDER BY order_no');
            if(count($par_sentences) + 1 == $order) {
                $isLastSentence = true;
            }
            
            // $par_sentences = $this->getSentences('WHERE content_id = '.$content.' AND paragraph_no = '.$paragraph.' ORDER BY order_no');
            // $order = end($par_sentences)['order_no'] + 1;
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "INSERT INTO tbl_sentences(article_id,content_id,paragraph_no,order_no,sentence)VALUES('$article', '$content', '$paragraph', '$order', '$sentence')";
                if($conn->query($sql) == TRUE) {
                    # insert to log 
                    $sid = $conn->insert_id;
                    $action = trim($user['fname'])." ".trim($user['lname'])." added the sentence";
                    $from_ = $data['new_sentence_add'];
                    $log = $this->addSentLog($article, $sid, $action, $from_, '');
                    
                    # adjust positioning of other sentences in the paragraph (if needed)
                    $last_id = $conn->insert_id;
                    if(!$isLastSentence) {
                        foreach($par_sentences as $sentence) {
                            if($sentence['id'] != $last_id && intval($sentence['order_no']) >= $order) {
                                $new_order = intval($sentence['order_no']) + 1;
                                $conn->query("UPDATE tbl_sentences SET order_no = '".$new_order."' WHERE id = ".$sentence['id']);
                            }
                        }
                    }
                    
                    # recheck export status of article associate with this sentence
                    $export_status = $this->updateExportStatusBySentence($last_id);
                    
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function updateSentence($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            $sentence = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $data['new_sentence'])));
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "UPDATE tbl_sentences SET sentence = '".$sentence."' WHERE id = ".$data['sent_id'];
                if($conn->query($sql) == TRUE) {
                    # insert to log if changes occur
                    $user = $this->getUserById($_SESSION['login_id']);
                    
                    if($data['orig_sentence2'] != $data['new_sentence']) {
                        $action = trim($user['fname'])." ".trim($user['lname'])." edited this sentence";
                        $from_ = $data['orig_sentence2'];
                        $to_ = $data['new_sentence'];
                        $log = $this->addSentLog($data['article_id'], $data['sent_id'], $action, $from_, $to_);
                    }
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function rewriteSentence($data) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            $sentence = mysqli_real_escape_string($conn,trim(preg_replace('!\s+!', ' ', $data['r_new_sentence'])));
            $created_at = date("Y-m-d H:i:s");
            $user = $this->getUserById($_SESSION['login_id']);
            $rewrite = $this->getRewrittenById($data['r_rewrite_id']);
            
            //update status of original rewrite
            $this->updateStatus1($data['r_rewrite_id'], 3);
            
            # log status
            $action = trim($user['fname'])." ".trim($user['lname'])." set the status of this rewrite";
            $from_ = "REJECTED";
            $to_ = "REJECTED & REWRITTEN";
            $log = $this->addLog($data['r_rewrite_id'], $action, $from_, $to_);
            
            //collect data
            $uid = $user['id'];
            $aid = $rewrite['article_id'];
            $sid = $rewrite['sentence_id'];
            $order = $rewrite['order_no'];
            $paragraph = $rewrite['paragraph_no'];
            
            $article = $this->getArticleById($aid);
            $cid = $article['category_id'];
            
            $messages = array_filter(explode(".", $data['r_err_msg']));
            if(!empty($messages)) {
                return $messages;
            }
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "INSERT INTO tbl_rewrites(user_id,article_id,category_id,sentence_id,order_no,paragraph_no,sentence,created_at,updated_at,is_reject_rewrite)VALUES('$uid', '$aid', '$cid', '$sid', '$order', '$paragraph', '$sentence', '$created_at', '$created_at', '1')";
                if($conn->query($sql) == TRUE) {
                    # insert to log 
                    $rid = $conn->insert_id;
                    $action = trim($user['fname'])." ".trim($user['lname'])." rewrote the sentence";
                    $from_ = $data['r_orig_sentence2'];
                    $to_ = $data['r_new_sentence'];
                    $log = $this->addRewriteLog($rewrite['sentence_id'],$rid, $action, $from_, $to_);
                    
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function deleteSentence($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            $sentence = $this->getSentenceById($id);
            
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "DELETE FROM tbl_sentences WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    //delete rewrites associated with this sentence
                    $rewrites = $this->getRewrittenArticles('WHERE sentence_id = '.$id);
                    if(!empty($rewrites)) {
                        foreach($rewrites as $rewrite) {
                            $this->deleteRewrite($rewrite['id']);
                        }
                    }
                    
                    # recheck export status of article associate with this sentence
                    $export_status = $this->updateExportStatusByArticle($sentence['article_id']);
                    
                    //delete logs associated with this sentence
                    // $sql = "DELETE FROM tbl_logs WHERE sentence_id = ".$id;
                    // if($conn->query($sql) == TRUE) {
                    //     $conn->close();
                    //     return true;
                    // }
                    
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        function adjustSentencesOrder($id, $new_order) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "UPDATE tbl_sentences SET order_no = '".$new_order."' WHERE id = ".$id;
                if($conn->query($sql) == TRUE) {
                    $conn->close();
                    return true;
                } else {
                    $conn->close();
                    return false;
                }
            }
        }
        
        //check user qualifications
        function canAccessPortal($uid) { //there is also a copy on this function in core.php
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                // $sql = "SELECT * FROM tbl_assignments WHERE (role_id = 1 OR role_id = 2) AND user_id = ".$uid;
                $sql = "SELECT * FROM tbl_assignments WHERE user_id = ".$uid;
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0) {
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function isEditor($uid) { //EDITOR
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE (role_id = 1 OR role_id = 3 OR role_id = 6) AND user_id = ".$uid;
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function isWriter($uid) { //WRITER
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE (role_id = 1 OR role_id = 5 OR role_id = 6) AND user_id = ".$uid;
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function isContentAdmin($uid) { //WRITER
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE (role_id = 1 OR role_id = 4) AND user_id = ".$uid;
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        function isAdmin($id) {
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            if($conn->connect_error){
                die("connection failed: ". $conn->connect_error);
            } else {
                $sql = "SELECT * FROM tbl_assignments WHERE role_id = 1 AND user_id = ".$id;
                $result = $conn->query($sql);
                if(!empty($result) && $result->num_rows > 0){
                    $conn->close();
                    return true;
                } else{
                    $conn->close();
                    return false;
                }
            }
        }
        
        //HELPER FUNCTIONS HERE
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
        function fetcharticlesfromCategory($cat_id){
            $conn = new mysqli(DB_HOSTAA,DB_USERAA,DB_PASSAA,DB_NAMEAA);
            // $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                    $sql = "Select
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
                              a.updated_at,
                              b.id
                            FROM tbl_rewrites a, tbl_new_articles b
                            WHERE a.article_id = b.id AND b.category_id = $cat_id ORDER BY created_at DESC";
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
        function fetchCategoryList($id=""){
            $conn = new mysqli(DB_HOST2,DB_USER2,DB_PASS2,DB_NAME2);
            if($conn->connect_error){
                die("connectioned failed: ". $conn->connect_error);
                return false;
            } else {
                    if($id=="")$condition ="";
                    else $condition = "WHERE id=".$id;
                    $sql = "SELECT * FROM tbl_category $condition ORDER BY created_at DESC";
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
        function addOrdinalNumberSuffix($num) {
            if (!in_array(($num % 100),array(11,12,13))){ 
                switch ($num % 10) {
                    // Handle 1st, 2nd, 3rd
                    case 1:  return $num.'st';
                    case 2:  return $num.'nd';
                    case 3:  return $num.'rd';
                }
            }
            return $num.'th';
        }
        
        function convert_smart_quotes($string)  {
            // $new_string = $this->htmlallentities($string);
            
            //handles Microsoft-encoded quotes (“ ” ‘ ’)
            $search = array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151)); 
            $replace = array("'", "'", '"', '"', '-', '-'); 
            return str_replace($search, $replace, $string); 
        }
    }
?>