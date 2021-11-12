



<?php

$data = array('model'=>'user', 'order'=>'', 'condition'=>"");
$users = $app->getRecord2($data);
$users = $users['data'];
$dusers = array();
foreach($users as $k=>$v){
    $dusers[$v['id']]=$v['user_Fname'];
}

$data = array('model'=>'logs', 'order'=>'order by date DESC', 'condition'=>"");
$logs = $app->getRecord2($data);
$logs = $logs['data'];
//echo "<br><br><br><br>".json_encode($logs);
?>

<div class="message-box">
    <?php

    if($response['action']=="delete"){ $daction = "deleted"; }
    if( $response['action']=="update"){ $daction = "updated"; }
    if( $response['action']=="create"){ $daction = "created"; }



    ?>

</div>

<section class="content" >


    <div class="row">

        <div class="col-md-12">



            <div class="widget-content ">
                <div">

                <?php if( $response['message']=="Successful"){
                    echo '<br><div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
                    <strong>Success!</strong> Record '.$daction.' successfully.
                </div>';
                }
                ?>



            </div>
            <div id="print" class="myDivToPrint">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title" style="font-weight: bold;">Access Logs</h3>
                        <div style="padding:7px; text-align: right;" class="pull-right">
                            <button  class="btn btn-primary btn-sm btn-click-view" onclick="PrintElem('print', 'Tenant Recod')"><i class="icon-print"></i> Print</button>

                            

                        </div>
                    </div>
                   



                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Website Folder</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Feature</th>
                                <th>Item</th>
                                <th>Old</th>
                                <th>New</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $c=0; foreach ($logs as $key => $value): $c++;
                               
                                ?>
                                <tr>
                                    <td><?php echo $value['webfile']; ?></td>
                                    <td><?php echo $dusers[$value['uid']]; ?></td>
                                    <td><?php echo $value['action']; ?></td>
                                    <td><?php echo $value['feature']; ?></td>
                                    <td><?php echo $value['title']; ?></td>
                                    <td><?php echo $value['oldvalue']; ?></td>
                                    <td><?php echo $value['newvalue']; ?></td>
                                    <td><?php echo $value['date']; ?></td>
                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Category Name</th>
                                <th style="width: 30%;">Template</th>
                               
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>