<?php

        // if($app->connect()){
        //     echo json_encode(array('status' => '300', 'message'=>'Sorry for the inconvenience, we are under maintenance.'));
        // }
        // else{
            
            $sql = "SELECT * FROM tbl_employee";
//          
echo $sql;
            $result = mysqli_query($app->connect(),$sql) or die(mysqli_connect_error());
            
            //$total = mysqli_num_rows($result);
            $index = 0;
            $responseData=array();
            $fetchData2 = mysqli_fetch_assoc($result);
            echo json_encode($fetchData2);
            while($fetchData = mysqli_fetch_assoc($result)){
               
                $responseData[$index] = $fetchData;
                $index++;
            }
            echo json_encode($fetchData2);
            


           

        //}

?>