<!-- Main content -->

<script>


    function redirect(page, whereto){
        var url = window.location.href
        var rawUri = url.split(page);
        var id = document.getElementsByName('target');
        var rate_value;


        if(whereto=='update'){
            for(var i = 0; i < id.length; i++){
                if(id[i].checked){
                    rate_value = id[i].value;
                }
            }

            if (typeof rate_value !== "undefined") {
                url = url.split("&");
                url = url[0];
                window.location = url + "-new&id="+rate_value;
            }else{
                document.getElementById('message_box').innerHTML = "";
                var d1 = document.getElementById('message_box');
                d1.insertAdjacentHTML('beforeend', '<div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-info"></i> Alert!</h4>Please select record first!.</div>');
            }
        }else{
            url = url.split("&");
            url = url[0];
            window.location = url + '-new'
        }
        //window.location = url + "-new&id="
    }

    function checkdel(page){
        var url = window.location.href
        var id = document.getElementsByName('target');
        var rate_value;
        for(var i = 0; i < id.length; i++){
            if(id[i].checked){
                rate_value = id[i].value;
            }
        }
        if (typeof rate_value !== "undefined") {
            url = url.split("&");
            url = url[0];
            window.location = url + "&delete="+rate_value;
        }else{
            document.getElementById('message_box').innerHTML = "";
            var d1 = document.getElementById('message_box');
            d1.insertAdjacentHTML('beforeend', '<div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-info"></i> Alert!</h4>Please select record first!.</div>');
        }
    }


</script>


<?php
$response['message'] = "";



if(isset($_GET['fd'])){
    $dataS = array(
        'model'=>'user',
        'conditions'=>array(
            'id'=>$_GET['fd']
        )
    );
    $response = $app->delete($dataS);
}

$data = array('model'=>'user', 'keys'=>'*');
$fetched = $app->getRecord($data);
$users = $fetched['data'];

?>
<section class="content" >


    <div class="row">
        <!--  -->
        <div class="col-xs-12">
            <div class="pull-right" style="padding-bottom:10px;">
                <label title="Create" style="cursor: pointer;" class="btn btn-success fa fa-plus-square" data-toggle="modal" data-target="#createModal" onclick="redirect('<?php echo $page; ?>','create')">&nbsp;  Create</label>
                <!--  <label  title="Update" style="cursor: pointer;"  class="btn btn-warning  fa fa-search" data-toggle="modal">&nbsp;  View</label> -->
                <label  title="Update" style="cursor: pointer;" class="btn btn-info  fa fa-pencil-square-o" data-toggle="modal" onclick="redirect('<?php echo $page; ?>','update')">&nbsp;  Update</label>
                <!--<label title="Delete" style="cursor: pointer;" id="del_b" class="btn btn-danger  fa  fa-trash-o " data-toggle="modal" onclick="checkdel('<?php echo $page; ?>')">&nbsp;  Delete</label>-->
            </div>
        </div>

        <div class="col-xs-12" id="message_box">
            <?php

            if(isset($_GET['delete'])){

                echo '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                Are You sure you want to delete this record? &nbsp;&nbsp;<a href="?page='.$page.'&fd='.$_GET['delete'].'">Click Here If Yes</a> 
              </div>';
            }
            if($response['message']=="Successful"){

                echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                Record Deleted Successfully!
              </div>';
            }


            ?>

        </div>







        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">

                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="row"><div class="col-sm-8">
                                    <h3 class="box-title" >USERS TABLE</h3>
                                </div>
                                <!--  <div class="col-sm-4">Search:<input type="search" class="form-control input-sm search" placeholder="" aria-controls="example1"></div> -->
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Designation</th>
                                <th>Usertype</th>

                            </tr>
                            </thead>
                            <tbody id="table_body">
                            <?php

                            $usertype = $app->getUserTypes();
                            foreach ($users as $key => $value) :

                                if($value['id']!=1):
                                    ?>
                                    <tr >
                                        <td>
                                            <input type="radio"  style="opacity: 100;" name="target" value="<?php echo $value['id']; ?>"
                                                <?php if(isset($_GET['delete'])){ if($_GET['delete']==$value['id']){ echo "checked"; } } ?>
                                            >
                                            <?php

                                                echo $value['user_Fname']." ".$value['user_Mname']." ".$value['user_Lname'];



                                            ?>
                                        </td>
                                        <td><?php echo $value['user_Address']; ?></td>
                                        <td><?php echo $value['user_Designation']; ?></td>
                                        <td><?php echo $usertype[$value['user_Usertype']]; ?></td>

                                    </tr>
                                <?php endif; endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Designation</th>
                                <th>Usertype</th>

                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->


                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <div id="result"></div>
    <!-- /.row -->
</section>



<!-- /.content -->



<script>


    $(document).ready(function () {





    });


    $(function(){
        $(".search").keyup(function()
        {

            var searchid = $(this).val();
            var dataString = "code="+searchid+"&name="+searchid+"&model=supplier";
            if(searchid!='')
            {
                $.ajax({
                    type: "POST",
                    url: "supplier-search.php",
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                        $("#table_body").html(html).show();
                        //alert(html);
                    }
                });
            }else{
                // $("#table_body").empty();
                // return false;
                var dataString = "code=&name=&model=supplier";
                $.ajax({
                    type: "POST",
                    url: "supplier-search.php",
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                        $("#table_body").html(html).show();
                        //alert(html);
                    }
                });
            }
        });
    });



</script>
 
  
