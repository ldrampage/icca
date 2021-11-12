<!-- Main content -->
<?php
$response=array();
$response['message'] = "";



if(isset($_POST['btn_save'])){

    if($_POST['upass']==$_POST['upass2']){
        $date = date('Y-m-d');
        $dataCheck = array(
            'model'=>'user',
            'inputs'=>array(
                'user_Uname'=> $_POST['uname']
            )
        );

        if($_POST['usertype']<5){
            $user_cct = 1;
        }else{
            $user_cct = 2;
        }

        if(isset($_POST['id'])){
            $data = array(
                'model'=>'user',
                'inputs'=>array(
                    'fname'=> $_POST['fname'],
                    'mname'=> $_POST['mname'],
                    'lname'=> $_POST['lname'],
                    'address'=> $_POST['address'],
                    'usertype'=> $_POST['usertype'],
                    'designation'=> $_POST['designation'],
                    'uname'=> $_POST['uname'],
                    
                    'photo'=> 'uploads/user.png',
                    'date'=> $date
                ),
                'conditions'=>array(
                    'id'=>$_POST['id']
                )
            );
            
            if(trim($_POST['upass'])!=""){
              $data['inputs']['upass']= sha1($_POST['upass']);
            }
            $response = $app->update($data);
            $response['message'] = "Successful";
        }else{
            $n = $app->checkExist($dataCheck);
            if($n==0){
                $data = array(
                    'model'=>'user',
                    //'keys'=>'user_Fname, user_Mname, user_Lname, user_Address, user_Usertype, user_Photo, user_Uname, user_Upass, user_Designation, user_Date',
                    'inputs'=>array(
                        'fname'=> $_POST['fname'],
                        'mname'=> $_POST['mname'],
                        'lname'=> $_POST['lname'],
                        'address'=> $_POST['address'],
                        'usertype'=> $_POST['usertype'],
                        'designation'=> $_POST['designation'],
                        'uname'=> $_POST['uname'],
                        'upass'=> sha1($_POST['upass']),
                        'photo'=> 'uploads/user.png',
                        'date'=> $date
                    ),
                );
                $response = $app->create($data);
                $response['message'] = "Successful";
                //echo json_encode($response);
            }else{
                $response['message'] = "Exist";
            }
        }
    }
    else{
        $response['message'] = "Password";
    }
}else{
    $response['message'] = "Failed";
}


if(isset($_GET['id'])){
    $action = "Update";
    $dataS = array(
        'model'=>'user',
        'inputs'=>array('*'),
        'conditions'=>array(
            'id'=>$_GET['id']
        )
    );
    $real_value = $app->getRecord($dataS);
    $rvalue=$real_value['data'][0];
}else{ $action = "Create"; }


?>
<section class="content" >


    <div class="row">
        <div class="col-xs-12">

            <?php

            if($response['message']=="Successful"){

                echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                Record Saved Successfully!
              </div>';
            }
            if($response['message']=="Exist"){

                echo '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Sorry, User Name already Exist!.
              </div>';
            }
            if($response['message']=="Password"){

                echo '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Sorry, password did not matched!.
              </div>';
            }

            ?>


        </div>
        <div class="col-xs-12">
            <form name="user" method="post" >
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                        <h4 class="modal-title" id="myModalLabel"><?php echo $action; ?> User</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-8">
                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>First Name</label>
                                    <?php if(isset($_GET['id'])): ?>
                                        <input type="hidden" class="form-control" name="id" value="<?php echo $_GET['id']; ?>" required />
                                    <?php endif; ?>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="text" class="form-control" placeholder="First Name" name="fname" <?php if(isset($_GET['id'])){ echo "value='".$rvalue['user_Fname']."'"; } ?> required />
                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Middle Name</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="text" class="form-control" placeholder="Middle Name" name="mname" <?php if(isset($_GET['id'])){ echo "value='".$rvalue['user_Mname']."'"; } ?> required />
                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Last Name</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="text" class="form-control" placeholder="Last Name" name="lname" <?php if(isset($_GET['id'])){ echo "value='".$rvalue['user_Lname']."'"; } ?> required />
                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Address</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="text" class="form-control" placeholder="Address" name="address" required <?php if(isset($_GET['id'])){ echo "value='".$rvalue['user_Address']."'"; } ?> />
                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Designation</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="text" class="form-control" placeholder="Designation" name="designation"  required <?php if(isset($_GET['id'])){ echo "value='".$rvalue['user_Designation']."'"; } ?> />
                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>User Type:</label>
                                    <select  class="form-control" name="usertype">

                                        <?php
                                        $usertype = $app->getUserTypes();

                                        if(isset($_GET['id'])){
                                            echo '<option value="'.$rvalue['user_Usertype'].'">'.$usertype[$rvalue['user_Usertype']].'</option>';
                                            $p = $rvalue['user_Usertype'];
                                        }else{
                                            $p = 0;
                                            echo '<option value="0">SELECT</option>';
                                        }

                                        for($x=1;$x<=sizeOf($usertype);$x++){
                                            if($p!=$x){
                                                echo '<option value="'.$x.'">'.$usertype[$x].'</option>';
                                            }
                                        }
                                        ?>

                                    </select>

                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Username</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="text" class="form-control" placeholder="Username" name="uname" <?php if(isset($_GET['id'])){ echo "value='".$rvalue['user_Uname']."'"; } ?>  required />
                                </div>
                                <?php if(isset($_GET['id'])):  ?>

                                <div class="form-group" style="margin-bottom: 0px; margin-top:5px; color: #f39c12;">
                                    <label>Leave password blank to remain existing.</label>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Password</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="password" class="form-control" placeholder="Password" name="upass" <?php if(isset($_GET['id'])){ echo "value=''"; } ?>  <?php if(!isset($_GET['id'])): echo "required"; endif; ?> />
                                </div>

                                <div class="form-group" style="margin-bottom: 0px; ">
                                    <label>Re-password</label>
                                    <!-- <span class="input-group-addon"><i class="fa fa-envelope"></i></span> -->
                                    <input type="password" class="form-control" placeholder="Re-password" name="upass2" <?php if(isset($_GET['id'])){ echo "value=''"; } ?>  <?php if(!isset($_GET['id'])): echo "required"; endif; ?> />
                                </div>

                                <!-- <input type="submit" value="Register" name="btn_sub"> -->
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="btn_save" class="btn btn-success fa fa-plus-square btn-sm" value="<?php echo $action; ?>">
                    </div>
                </div>
            </form>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
 
  
