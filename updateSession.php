<?php
        error_reporting(E_ALL); ini_set('display_errors', '1');
        include "core/core.php";
        $app = new mckirby();
        date_default_timezone_set('America/New_York');
        $date = date('Y-m-d h:i:s', time());
        $p['user_Lastsession']=$date; 
        $p['id']=$_POST['isd'];
        $df = array("model"=>"employee", "values"=>" last_session='".$date."'", "condition"=>" WHERE id = '".$p['id']."'");
        // $update = $app->update2($df,1);
        // echo $date;


?>