<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    $action = "";
    
    $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
    if(!in_array($_SESSION['login_id'], [4,43,44])) {
        header('Location: http://icca.authoritativecontent.net/index.php'); 
    }
    
    //adding a credential
    if(isset($_POST['addCredential'])) {
        $message = $icca_new_obj->addCredential($_POST);
        $action = $message ? "added" : "adding";
    }
    
    //updating a credential
    if(isset($_POST['editCredential'])) {
        $message = $icca_new_obj->updateCredential($_POST);
        $action = $message ? 'updated' : 'updating';
    }
?>

<section class="content" >
    <?php if($message != "") { ?>
        <div class="row">
            <?php if(is_array($message)) { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        <?= $message[0] ?>
                    </div>
                </div>
            <?php } else if($message == true) { ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        API credential was <?= $action ?> successfully.
                     </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                        Error occurred when <?= $action ?> the API credential. Please try again later.
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="widget-content ">
                <div id="print" class="myDivToPrint">
                    <div class="box">
                        <div class="box-header">
                            <div class="row"> 
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h3 class="box-title" style="font-weight: bold;">API Credentials</h3>
                                    <div class="pull-right" style="margin-bottom:5px;">
                                        <a href="javascript:showAddModal()">
                                            <label class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add credential</label>
                                        </a> 
                                    </div>
                                </div>
                            </div> 
                            
                            <hr style="margin-top: 5px;">
                        </div>
                        <div class="box-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-130">API</th>
                                        <th class="mwidth-130">Username</th>
                                        <th class="mwidth-130">API Key</th>
                                        <th class="mwidth-180">Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                   <?php 
                                        $count = 0;
                                        $credentials = $icca_new_obj->getCredentials('ORDER BY api');
                                        foreach ($credentials as $cred):
                                    ?>
                                        <tr>
                                            <td><?= ++$count; ?></td>
                                            <td><?= $cred['api'] ?></td>
                                            <td><?= $cred['username'] ?></td>
                                            <td><?= $cred['api_key'] ?></td>
                                            <td class="center">
                                                <a href="javascript:showEditModal(<?= $cred['id'] ?>, '<?= $cred['api'] ?>', '<?= $cred['username'] ?>', '<?= $cred['api_key'] ?>')">
                                                    <button  class="btn btn-warning btn-xs">
                                                        <i class="fa fa-pencil-alt"></i>&nbsp;&nbsp;Edit
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?> 
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-130">API</th>
                                        <th class="mwidth-130">Username</th>
                                        <th class="mwidth-130">API Key</th>
                                        <th class="mwidth-180">Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add credential modal -->
    <div class="modal fade" id="addModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Add Credential</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>API:</label> 
                                    <input type="text" class="form-control" name="add_api" id="add_api" required>
                                </div>
                                <div class="form-group">
                                    <label>Username:</label> 
                                    <input type="text" class="form-control" name="add_user" id="add_user" required>
                                </div>
                                <div class="form-group">
                                    <label>API Key:</label> 
                                    <input type="text" class="form-control" name="add_key" id="add_key" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-success" type="submit" id="addCredential" name="addCredential">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add credential modal -->
    
    <!-- Edit credential modal -->
    <div class="modal fade" id="editModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit Credential</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="cred_id" id="cred_id">
                                <div class="form-group">
                                    <label>API:</label> 
                                    <p id="edit_api"></p>
                                </div>
                                <div class="form-group">
                                    <label>Username:</label> 
                                    <input type="text" class="form-control" name="edit_user" id="edit_user" required>
                                </div>
                                <div class="form-group">
                                    <label>API Key:</label> 
                                    <input type="text" class="form-control" name="edit_key" id="edit_key" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-warning" type="submit" id="editCredential" name="editCredential">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit credential modal -->
</section>

<script>
    function showAddModal() {
        $('#addModal').modal('show');
    }
    
    function showEditModal(id, api, uname, pass) {
        $('#cred_id').val(id);
        $('#edit_api').html(api);
        $('#edit_user').val(uname);
        $('#edit_key').val(pass);
        
        $('#editModal').modal('show');
    }
</script>

