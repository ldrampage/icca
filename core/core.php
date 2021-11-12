<?php
    error_reporting(E_ALL); ini_set('display_errors', '1');
    ini_set('max_execution_time', 1000);
    // date_default_timezone_set("Asia/Taipei");
    
    //save rewritten articles
    define("DB_AHOST", "localhost");
    define("DB_AUSER", "acllcmas_icca_user");
    define("DB_APASS", "v@4K548#M{/;X[$<");
    define("DB_ANAME", "acllcmas_icca");
    
    define("DB_HOST", "localhost");
    define("DB_USER", "acllcmas_uacm01");
    define("DB_PASS", "orxup)BVslZW");
    define("DB_NAME", "acllcmas_acacp");
    
    //hr credentials use for login
    // define("DB_LHOST", "localhost");
    // define("DB_LUSER", "acllcmas_uhr01");
    // define("DB_LPASS", '*wex(C{K"74f');
    // define("DB_LNAME", "acllcmas_mhr");
    
    //hr credentials (from backoff3)
    
    define("DB_LHOST", "68.66.214.205");
    define("DB_LUSER", "backoff3_tmuhr");
    define("DB_LPASS", "sTmwbw4a0Ylp");
    define("DB_LNAME", "backoff3_mhr");
    
    /*
    define("DB_LHOST", "localhost");
    define("DB_LUSER", "acllcmas_nmuhr");
    define("DB_LPASS", "_kc?~Ew[%5,z");
    define("DB_LNAME", "acllcmas_mhr");*/
    
    
    define("SITE_URL", "http://icca.authoritativecontent.net/");
    define("HR_URL", "https://hr.backoffice-operations.com/");
    
    define("URL_SEPARATOR", "/");
    define("FILE_SEPARATOR", "\\");
    define("ROOT_DIR", realpath(__DIR__ . '/..'));
    define("CORE_DIR", dirname(__FILE__));
    define('modelPreName', 'tbl_');
    define('preName', '');
    define('PREID', 'AC-');
    
    define('MAINURL', 'http://icca.authoritativecontent.net/');

    define("MYBLUE","#00c0ef");
    define("MYRED","#dd4b39");
    define("MYGREEN","#00a65a");
    define("MYGOLD","#f39c12");
    
    define("EMAILSENDER","hr@authoritativecontentllc.com");
    define("NOREPLY","noreply@authoritativecontentllc.com");
    define("EMAILFOOTER","Authoritative Content LLC.");
    define("INCLUDEONLY","PHILIPPINES");
    
    $db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $dbA = mysqli_connect(DB_AHOST,DB_AUSER,DB_APASS,DB_ANAME);
    
    //ERROR MESSAGES
    // 1. mysql connection failed
    define("DB_CONNECTION_FAILED", "Sorry, centralized database is out of reach. Please call support for details.");
    //SUCCESS MESSAGES
    define("DB_CONNECTION_SUCCESS", "Connection has been established.");

    class mckirby {
        function saveLog($action, $eid, $model, $oldvalue, $newvalue, $condition=null){
            if($condition==null){ $condition=""; } 
            $date = date('Y-m-d h:i:s', time());
            $data = array("model"=>"logs", 
                              "keys"=>"action, tmodel, date, oldvalue, newvalue, eid, conditionstring",
                              "values"=>"'".$action."', '".$model."', '".$date."', '".$oldvalue."', '".$newvalue."', '".$eid."', '".$condition."'");
                              
            $logs = $this->create2($data);                      
        }
        
        function getMyACL($id){
          $data = array('model'=>'acl', 'condition'=>" WHERE emp_id = '".$id."'",'order'=>' order by feature_code');
          $department = $this->getRecord2($data);
          $department = $department['data'];
          $ar = array();
          foreach ($department as $key => $value) {
            $ar[$value['id']] = $value;
          }
          return $ar;
        }
        
        function getCategories(){
          $data = array('model'=>'category', 'condition'=>"",'order'=>' order by name');
          $department = $this->fetchIccaRecord($data); 
          $department = $department['data'];
          $ar = array();
          foreach ($department as $key => $value) {
            $ar[$value['id']] = $value;
          }
          return $ar;
        }
        
        function getUserTypes(){
            return array(1=>"Administrator", 2=>"Development Team", 3=>"Content Management Team");
           }
   
        function ACLfeaturesL($id){
          $data = array("model"=>"acl", "condition"=>" WHERE emp_id = '".$id."'");
          $ret = $this->getRecord($data);
          $new_array = array();
          foreach($ret['data'] as $k => $v){
             $new_array[trim($v['feature_code'])] = $v['fcontrol'];
          }
          $new_array['profile']=1;
          return $new_array;
      }
    
        function connect($data=null){
            return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        
        function connectArticle($data=null){
            return mysqli_connect(DB_AHOST, DB_AUSER, DB_APASS, DB_ANAME);
        }
        
        function connectLogin($data=null){
            $con = mysqli_connect(DB_LHOST, DB_LUSER, DB_LPASS, DB_LNAME);
            if (!$con) {
                die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
            } else {
               return $con; 
            }
             
        }
        
        /**** functionalities related to logging in ****/
    
        function checkUserExist($data){
              $model = modelPreName . "user";
              if (array_key_exists("method",$data)){
              	if($data['method']=="login"){
    	                $sql = "SELECT * FROM $model WHERE user_Uname = '".$data['inputs']['uname']."' AND user_Upass = '".sha1($data['inputs']['upass'])."'";
              	}else{
    	                $sql = "SELECT * FROM $model WHERE user_Uname = '".$data['inputs']['uname']."'";
                  }
              }else{
                      $sql = "SELECT * FROM $model WHERE user_Uname = '".$data['inputs']['uname']."'";
                  }
              $result = mysqli_query(self::connect(),$sql) or die(mysqli_connect_error()); 
              //echo $sql;
              if(mysqli_num_rows($result)==0){
                 return mysqli_num_rows($result); 
              }else{
                return mysqli_fetch_assoc($result);
              }
        }
        
        function canAccessPortal($uid) {
            // $sql = "SELECT * FROM tbl_assignments WHERE (role_id = 1 OR role_id = 2) AND user_id = ".$uid;
            $sql = "SELECT * FROM tbl_assignments WHERE user_id = ".$uid;
            $result = mysqli_query(self::connectArticle(),$sql) or die(mysqli_connect_error()); 
            
            if(mysqli_num_rows($result) == 0) {
                return false;
            } else {
                return true;
            }
        }
    
        function userLogin($data){
              $model = modelPreName . "employee";
              $sql = "SELECT * FROM $model WHERE ( un = '".$data['inputs']['uname']."' OR email = '".$data['inputs']['uname']."') AND up = '".sha1($data['inputs']['upass'])."'";
              $result = mysqli_query(self::connectLogin(),$sql) or die(mysqli_connect_error()); 
              //echo $sql;
              if(mysqli_num_rows($result)==0) {
                 return mysqli_num_rows($result); 
              } else {
                $data = mysqli_fetch_assoc($result);
                
                //check if user has granted access to portal
                if(!self::canAccessPortal($data['id'])) {
                    return -1;
                }
                return $data;
              }
        }

        function getRecordById($model,$id){
            //$model = modelPreName . $model;
            $dataq = array(
              'model'=>$model,
              'condition'=>" WHERE id = '".$id."'"
            );
            //echo json_encode($dataq);
            return $this->getRecord2($dataq);
        }
        function getRecordByIdL($model,$id){
            //$model = modelPreName . $model;
            $dataq = array(
              'model'=>$model,
              'condition'=>" WHERE id = '".$id."'"
            );
           // echo json_encode($dataq);
            return $this->getRecordL($dataq);
        }
    
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
    
        function getRecord($data){
          if(!self::connect()){
              return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
          } 
          else{
              $model = 'tbl_'.$data['model'];
              $modelName = $data['model']. '_';
              $conditions = ''; $values = ''; $realCount = 0; $conditionCount = 0;
              if (array_key_exists("order",$data)){ 
                  $orderKey = $data['order'];
                  $order = " order by ".$orderKey; 
                  }
              else{ $order = ""; }
                  
              if (array_key_exists("keys",$data)){
                      if(is_array($data['keys'])){
                      foreach ($data['keys'] as $keyCount => $valueCount){$realCount++;}
                      $count = 0; 
                      if($realCount>0){
                      foreach ($data['keys'] as $key => $value){
                        if($count<$realCount && $count>0){ $comma = ', '; }else{ $comma = ' '; }
                        $values = $values . $comma . $modelName . ucfirst($value). " ";
                        $count++;
                      } }
                      else{
                          $values = " * ";
                      }
                    }else{
                      $values = $data['keys'];
                      //$values = " ".$data->keys." ";
                    }
              }else{
                      $values = " * ";
              }
                  //echo $values;
                  if (array_key_exists("conditions",$data)){
                    foreach ($data['conditions'] as $keyCount => $valueCount){$conditionCount++;}
                  if($conditionCount>0){
                      $conditions = ' WHERE ';
                      foreach ($data['conditions'] as $key => $value){
                          
                          if(strpos($value, "(AND)") !== false){
                              $operator = "AND ";
                              $value = str_replace(" (AND)","",$value);
                          }elseif(strpos($value, "(OR)") !== false){
                              $operator = "OR ";
                              $value = str_replace(" (OR)","",$value);
                          }else{
                            $operator = '';
                          }
                          if($key=="id"){
                            $conditions = $conditions . $key. " = '".$value."' " . $operator;
                          }else{
                            $conditions = $conditions . $modelName .ucfirst($key). " = '".$value."' " . $operator;
                          }
                      } 
                    }
                  }
                  //return json_encode(array('status' => '200', 'message'=>'Successful', 'affected'=>$conditions.' '.$values));
                  $sql = "SELECT $values FROM $model $conditions ".$order;
                 //echo $sql;
                  //echo $sql;
                  //return json_encode(array('status' => '200', 'message'=>'Successful', 'affected'=>$sql));
                  $result = mysqli_query(self::connect(),$sql) or die(mysqli_connect_error()); 
                  //echo 1;
                  $total = mysqli_num_rows($result);
                   $index = 0;
                   $responseData=array();
    
                   while($fetchData = mysqli_fetch_assoc($result)){
                       //return json_encode(array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$fetchData));
                      if (array_key_exists("user_Secpass",$fetchData)){
                        $fetchData['user_Secpass'] = '';
                      }
                       $responseData[$index] = $fetchData;
                       $index++;
                   }
                  //if($data->model=="user"){ $responseData['user_Secpass'] = ""; } 
                  if (array_key_exists("method",$data)){
                  if($data['method']=="login"){return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);}
                  else{return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);}
                  }
                  else{return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);}
                  
          }
        }
    
        function createL($data,$p=null){
            if(!self::connect()){
                return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
            }
            else{
                $mysqli = new mysqli(DB_LHOST, DB_LUSER, DB_LPASS, DB_LNAME);
                $keys = $data['keys'];
                $values = $data['values'];
                $model = 'tbl_'.$data['model'];
                $sql = "INSERT INTO $model($keys) VALUES($values)";
                //echo "<br>".$sql."<br>";
                if($mysqli->query($sql)){
                  //echo $data['model']." >".$p."<";
                    if($data['model']!="logs" && ($p==NULL || trim($p)=="")){
                        
                        $this->saveLog("CREATE", $_SESSION['login_id'], $data['model'], "", str_replace(']',"",str_replace('[',"",str_replace("'","\'",$values))), "id=\'".$mysqli->insert_id."\'");
                    }
                    return array('status' => '200', 'action'=>'create','message'=>'Successful', 'id'=>$mysqli->insert_id);
                }
               
            }
    
        }
    
        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    
        function getRecord2($data){
            $conn = self::connectLogin();
            if(!$conn) {
                return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
            } else {
                if (array_key_exists("condition",$data)){ $condition = $data['condition']; }else{ $condition = ""; }
                if (array_key_exists("order",$data)){ $order = $data['order']; }else{ $order = ""; }
                $model = 'tbl_'.$data['model'];
                $sql = "SELECT * FROM $model ".$condition." ".$order;
            
                $result = mysqli_query($conn,$sql) or die(mysqli_connect_error());
               
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
                    else { mysqli_close(self::connect()); return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData); }
                } else {
                    mysqli_close(self::connect());
                    return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);
                }
            }
        }
        
        
        function fetchIccaRecord($data){
            $conn = self::connectArticle();
            if(!$conn) {
                return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
            } else {
                if (array_key_exists("condition",$data)){ $condition = $data['condition']; }else{ $condition = ""; }
                if (array_key_exists("order",$data)){ $order = $data['order']; }else{ $order = ""; }
                $model = 'tbl_'.$data['model'];
                $sql = "SELECT * FROM $model ".$condition." ".$order;
             
                $result = mysqli_query($conn,$sql) or die(mysqli_connect_error());
               
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
                    else { mysqli_close($conn); return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData); }
                } else {
                    mysqli_close($conn);
                    return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);
                }
            }
        }
    
        function getRecordL($data){
            if(!self::connectLogin()){
                return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
            }
            else{
                if (array_key_exists("condition",$data)){ $condition = $data['condition']; }else{ $condition = ""; }
                if (array_key_exists("order",$data)){ $order = $data['order']; }else{ $order = ""; }
                $model = 'tbl_'.$data['model'];
                //echo $order;
                //echo $order;
                $sql = "SELECT * FROM $model ".$condition." ".$order;
    //            echo "<br>1.".$data['condition']."<br>";
    //            echo "2.".$data['conditions']."<br>";
    //            echo "3".$condition;
                //echo $sql."<br>";
                $result = mysqli_query(self::connectLogin(),$sql) or die(mysqli_connect_error());
                //echo 1;
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
                
                
    
    
                if (array_key_exists("method",$data)){
                    if($data['method']=="login"){return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);}
                    else{return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);}
                }
                else{
                   // echo json_encode($responseData);
                    return array('status' => '200', 'message'=>'Successful', 'affected'=>$total, 'data'=>$responseData);
                    
                }
    
            }
        }
    
        function checkExist($data){
    
          if(!self::connect()){
              return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
          } 
          else{
              $realCount = sizeOf($data['inputs']);
              $count=0;
              if($realCount >0) {$where = " WHERE ";}else{$where = '';}
              foreach ($data['inputs'] as $key => $value){
                        if($count==$realCount || $count==0){ $comma = ''; }else{ $comma = ' AND '; }
                          $where = $where . $comma . $key . " = '".mysqli_escape_string(self::connect(),  $value)."' ";
                        $count++;
              } 
               $model = "tbl_".$data['model']; 
               $sql = "SELECT * FROM $model ".$where;
               //echo $sql;
               //exit();
               $result = mysqli_query(self::connect(),$sql) or die(mysqli_connect_error()); 
               $total = mysqli_num_rows($result);
               return $total; 
          }
    
        }
    
        function uploadloc($id){
           $result =array();
            if(!self::connect()){
                return array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.');
            }
            else{
                
                 $mysqlix = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                 $sql = "SELECT * FROM tbl_attachments WHERE modelid = '".$id."' AND modelname = 'tickets'";
                 $result = $mysqlix->query($sql);
                 return $result;
                
                 
            }
    
        }
        
        /*** DASHBOARD ***/
        
        function time_elapsed_string($datetime, $full = false) {
            date_default_timezone_set('America/New_York');
            $actual= date('Y-m-d h:i:s', time());
            $now = new DateTime($actual);
            $ago = new DateTime($datetime);
            
            $diff = $now->diff($ago);
            
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
            
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
            
            if (!$full) $string = array_slice($string, 0, 1);
            if (array_key_exists('s', $string) && strpos($string['s'], 'second') !== false) {
              return "Online";
            } else {
                if($datetime != '0000-00-00 00:00:00') return $string ? implode(', ', $string) . ' ago' : 'Online';
                else return "Offline";
            }
        }
        
        //users allowed to load articles
        function userPermitted(){
            return array("14","28","1","43");
        }
        
    }
?>


