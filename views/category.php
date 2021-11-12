<?php 
// check if user is allowed
if(!$iccaFunc->isAllowed($_SESSION['login_id'], "createArticle")){
    echo "<script> alert('User not allowed'); location.href='index.php' </script>";
}

if(isset($_POST['create'])){
    $message = $iccaFunc->insertArticleCategory($_POST);
}

if(isset($_POST['edit'])){
    $message = $iccaFunc->editArticleCategory($_POST);
}
if(isset($_POST['apply'])){
    $data = array("cat_id" => $_POST['cat_id'], "user_id" => $_POST['user_id']);
    //print_r($data);
    //echo "<br>";
    unset($_POST['user_id']);
    unset($_POST['cat_id']);
    unset($_POST['articleList_length']);
    unset($_POST['apply']);
    
    //print_r($_POST);
    
    
    $message = $iccaFunc->applyCategoryToArticle($_POST, $data);
}
?>

<section class="content">
    <?php if(isset($message)): ?>
    <div class="row">
        
        <?php if($message == "apply-success"): ?>
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success</h4>
                Successfully applied category...
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($message == "success-edit"): ?>
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success</h4>
                Successfully edit category...
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($message == "success"): ?>
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success</h4>
                Successfully added...
            </div>
        </div>
        <?php endif; ?>
        
        <?php if($message == "failed"): ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                Something went wrong this time. Please try again...
            </div>
        </div>
        <?php endif; ?>
        <?php if($message == "category_name_exist"): ?>
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Category name exist!</h4>
                Unable to add, category name already exist...
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h1 class="box-title">
                        Category list
                    </h1>
                    <button class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addCategory"><i class="fa fa-plus"></i>&nbsp;Add category</button>
                </div>
                <div class="box-body">
                   
                    <div class="col-md-12">
                        <table id="categoryList" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">#</th>
                                    <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Name</th>
                                    <th class="mwidth-120" bgcolor="#2b415e" style="color: white;">Created By</th>
                                    <th class="mwidth-130" bgcolor="#2b415e" style="color: white;">Date Created</th>
                                    <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Action</th>
    
                                </tr>
                            </thead>
                            
                            <tfoot>
                                <tr>
                                    <th class="mwidth-200">#</th>
                                    <th class="mwidth-200">Name</th>
                                    <th class="mwidth-120">Created By</th>
                                    <th class="mwidth-130">Date Created</th>
                                    <th class="mwidth-200">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Add Category</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" id="addCategoryForm">
                                <b>Example name:</b>
                                <p>mysitearticle.com</p>
                                <div class="form-group" id="message"></div>
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required/> 
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="create" class="btn btn-sm btn-success ">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                            
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Edit Category</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" id="editCategoryForm">
                                <b>Current name:</b>
                                <p id="current_name"></p>
                                <div class="form-group" id="message"></div>
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required/>
                                    <input type="hidden" name="cat_id" id="cat_id">
                                    <input type="hidden" name="from_" id="from_">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="edit" class="btn btn-sm btn-success">Done</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                            
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="applyCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Apply Category</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title center" id="boxTitle"></h4>
                            <div class="form-group">
                                <input type="checkbox" id="checkAll"/>
                                <label>Select All</label>
                            </div>
                            <form method="post" id="applyCategoryForm">
                                <div class="form-group">
                                    <table id="articleList" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="mwidth-30" bgcolor="#2b415e" style="color: white;">Choose</th>
                                                <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Article Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $articles = $iccaFunc->fetchAllArticle();
                                            foreach($articles as $article){
                                        
                                        ?>
                                            <tr>
                                                <td><center><input type="checkbox" name="chosen_<?php echo $article['id']; ?>" class="checkboxes" value="<?php echo $article['id'] ?>"/></center></td>
                                                <td><?php echo $article['title']; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="mwidth-30">Choose</th>
                                                <th class="mwidth-200">Article Name</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <input type="hidden" name="cat_id" id="cat_id">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_id']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="apply" class="btn btn-sm btn-success">Done</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        const categoryListTb = $("#categoryList").DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : {
                "url": "/lib/categoryList.php",
            },
            "order":[[ 3, "desc" ]],
            "sDom": 'lrtip'
        });
        
        categoryListTb.on( 'order.dt search.dt', function () {
            categoryListTb.column(0, { search: 'applied', order: 'applied' }).nodes().each( function(cell, i) {
                cell.innerHTML = i+1;
            })
        }).draw();
        
        $("#addCategory").on("shown.bs.modal", function(){
            $(this).find("#name").val("");
            $(this).find("#name").focus();
            $(this).find("#message").html("");
        })
        
        $("#addCategoryForm").on("submit", function(){
            var name = $("#addCategory").find("#name").val();
            return true;
            
            // if(name.match(/(\w+)\.(\w+)/g)){
            //     //console.log("match");
            //     return true;
            // } else{
                
            //     $("#addCategory").find("#message").html("<strong style='color:red'>Incorrect category name.</strong>");
            //     $("#addCategory").find("#name").focus();
            //     return false;
            // }
        })
        
        $("#editCategoryForm").on("submit", function(){
            var name = $("#editCategory").find("#name").val();
            return true;

            // if(name.match(/(\w+)\.(\w+)/g)){
            //     //console.log("match");
            //     return true;
            // } else{
            //     $("#editCategory").find("#message").html("<strong style='color:red'>Incorrect category name.</strong>");
            //     $("#addCategory").find("#name").focus();
            //     return false;
            // }
        })
        
        $("#articleList").DataTable();
        
        $("#applyCategory").on("hide.bs.modal", function(){
            $(this).find(".checkboxes").prop("checked", false);
            $(this).find("#checkAll").prop("checked", false);
        })
        
        $("#checkAll").on("change", function(){
            
            if($(this).prop("checked") == true){
                $("#applyCategory").find(".checkboxes").prop("checked", true);
            } else{
                $("#applyCategory").find(".checkboxes").prop("checked", false);
            }
            
        })
        
        
    })
    
    function editCategory(id,name){
        $("#editCategory").modal('show');
        $("#editCategory").on("shown.bs.modal", function(){
            $(this).find("#current_name").html(name);
            $(this).find("#name").val("");
            $(this).find("#name").focus();
            $(this).find("#message").html("");
            $(this).find("#cat_id").val(id);
            $(this).find("#from_").val(name);
        })
    }
    
    function applyCategory(id,name){
        $("#applyCategory").modal('show');
        $("#applyCategory").on("shown.bs.modal", function(){
            $(this).find("#cat_id").val(id);
            $(this).find("#boxTitle").html(name);
            
        })
        
        
    }
    
</script>