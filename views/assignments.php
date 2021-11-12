<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    $action = "";
    
    $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
    if(!$isAdmin) {
        header('Location: http://icca.authoritativecontent.net/index.php'); 
    }
    
    $role_id = (isset($_GET['rid'])) ? $_GET['rid'] : 'all';
    $user_id = (isset($_GET['uid'])) ? $_GET['uid'] : 'all';
    
    //for role filter dropdown
    $roles = $icca_new_obj->getRoles(); 
    
    //adding an assignment
    if(isset($_POST['addAssignment'])) {
        $message = $icca_new_obj->addAssignment($_POST);
        $action = $message ? "added" : "adding";
    }
    
    //updating an assignment
    if(isset($_POST['updateAssignment'])) {
        $message = $icca_new_obj->updateAssignment($_POST);
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
            <?php } else if($message == true) { 
                $isAdmin = $icca_new_obj->isAdmin($_SESSION['login_id']);
                if(!$isAdmin) {
                    echo "<script>location.href = 'http://icca.authoritativecontent.net/index.php';</script>";
                }
            ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        Assignment was <?= $action ?> successfully.
                     </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                        Error occurred when <?= $action ?> the assignment. Please try again later.
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
                                    <h3 class="box-title" style="font-weight: bold;">Assignments</h3>
                                    <div class="pull-right" style="margin-bottom:5px;">
                                        <a href="javascript:showAddModal()">
                                            <label class="btn btn-xs btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add assignment</label>
                                        </a> 
                                    </div>
                                </div>
                            </div> 
                            
                            <hr style="margin-top: 5px;">
                            <div class="row"> 
                                <div class="col-md-3" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Employee:</h3> 
                                    <select class="form-control select2 select2-hidden-accessible" name="sel_employee" id="sel_employee" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="">Fetching...</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3" style="margin-bottom:10px">
                                    <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Role:</h3> 
                                    <select class="form-control select2 select2-hidden-accessible" name="sel_role" id="sel_role" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="">All</option>
                                        <?php 
                                            foreach($roles as $role) {
                                                $is_selected = ($sel_role == $role['id']) ? 'selected' : '';
                                                echo "<option value='".$role['id']."' ".$is_selected.">".$role['role']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="box-body table-responsive">
                            <table id="roleListTb" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-130">Name</th>
                                        <th class="mwidth-130">Department</th>
                                        <th class="mwidth-130">Role</th>
                                        <th class="mwidth-180">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-10">#</th>
                                        <th class="mwidth-130">Name</th>
                                        <th class="mwidth-130">Department</th>
                                        <th class="mwidth-130">Role</th>
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
    
    <!-- Add assignment modal -->
    <div class="modal fade" id="addModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="assignModalTitle">Add Assignment</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Employee:</label> 
                                    <select class="form-control select2 select2-hidden-accessible" name="c_user_id" id="c_user_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="">Fetching...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Role:
                                    </label> 
                                    <?php 
                                        foreach($roles as $role) {
                                            echo "<div class='radio'>
                                                    <label>
                                                        <input type='radio' name='role_id' id='c_role_".$role['id']."' value='".$role['id']."' required>
                                                        ".$role['role']." 
                                                    </label>
                                                </div>
                                            ";   
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-success" type="submit" id="addAssignment" name="addAssignment">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add assignment modal -->
    
    <!-- Edit assignment modal -->
    <div class="modal fade" id="editModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form role="form" method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="assignModalTitle">Edit Assignment</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="assign_id" id="assign_id">
                                <input type="hidden" name="u_user_id" id="u_user_id">
                                <input type="hidden" name="current_role" id="current_role">
                                <div class="form-group">
                                    <label>Name:</label> 
                                    <p id="user_name"></p>
                                </div>
                                <div class="form-group">
                                    <label>Department:</label> 
                                    <p id="dept_name"></p>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Role:
                                    </label> 
                                    <?php 
                                        foreach($roles as $role) {
                                            echo "<div class='radio'>
                                                    <label>
                                                        <input type='radio' name='role' id='u_role_".$role['id']."' value='".$role['id']."' required>
                                                        ".$role['role']." 
                                                    </label>
                                                </div>
                                            ";   
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group mb-0">
                            <button class="btn btn-warning" type="submit" id="updateAssignment" name="updateAssignment">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit assignment modal -->
</section>
<script>
    $(document).ready(function(){
        const roleListTb = $("#roleListTb").DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : "roleListDataTable.php",
        "order":[[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": [0],
                "visible": false
            }
        ],
        "sDom": 'lrtip',
        
            /*
        initComplete: function () {
        // Apply the search
        this.api().columns().every( function () {
            var that = this;
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                });
            });
        } */
    
        });
        
        // roleListTb.on( 'order.dt search.dt', function () {
        //     roleListTb.column(0, { search: 'applied', order: 'applied' }).nodes().each( function(cell, i) {
        //         cell.innerHTML = i+1;
        //     })
        // }).draw();
        
        $("#sel_employee").on("change", function(){
            roleListTb.column(1).search(this.value).draw();
        })
        
        $("#sel_role").on("change", function(){
            roleListTb.column(3).search(this.value).draw();
        })
        
    })
    
</script>
<script>
    //employee dropdown
    jQuery.ajax({
        url :"./ajax/assignments-employee-dropdown.php",
        type: "GET", 
        data: { 'type': 'filter' },
        success: function(data) {
            $('#sel_employee').html(data);
        },
        error: function(xhr, status, error){  
            console.log(xhr)
            console.log(xhr.responseText, status, error);
            alert("Error occurred when fetching employees for Employees dropdown. Please try again later.");
        }
    });
    
    //employee dropdown (add modal)
    jQuery.ajax({
        url :"./ajax/assignments-employee-dropdown.php",
        type: "GET", 
        data: { 'type': 'add' },
        success: function(data) {
            $('#c_user_id').html(data);
        },
        error: function(xhr, status, error){  
            console.log(xhr)
            console.log(xhr.responseText, status, error);
            alert("Error occurred when fetching employees for Employees dropdown. Please try again later.");
        }
    });

    function showAddModal() {
        $('#addModal').modal('show');
    }
    
    function showEditModal(id, name, dept_name, user_id, role_id) {
        $('#assign_id').val(id);
        $('#user_name').html(name);
        $('#dept_name').html(dept_name);
        $('#u_user_id').val(user_id);
        $('#current_role').val(role_id);
        $('#u_role_'+role_id).prop('checked', true);
        
        $('#editModal').modal('show');
    }
    
    function confirmDelete(id, name, role) {
        confirmed = confirm(`Are you sure you want to delete the role of ${name} as "${role}"?`);
        if (confirmed) {
            jQuery.ajax({
                url :"./ajax/delete-assignment.php",
                type: "POST", 
                data: { 'id' : id },
                success: function(data) {
                    alert(`Role was deleted successfully!`);
                    window.location.href = window.location.href;
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when deleting the role. Please try again later.");
                }
            });
        }
    }
</script>

