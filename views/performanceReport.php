<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1); 
    $message = "";
    
    //assigning an category to article
    if(isset($_POST['assignCategory'])) {
        echo "<script>console.log(".json_encode($_POST).")</script>";
        $message = $icca_new_obj->assignCategory($_POST);
    }
?>
    
<section class="content">
    <?php if($message != "") { ?>
        <div class="row">
            <?php if($message == true) { ?>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        Category was assigned to the article successfully!
                     </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
                        Error occurred when assigning the category to the article. Please try again later.
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>View</label>
                            <select class="form-control select2" id="viewMode">
                                <option value="0" <?php if(isset($_GET['mode'])) : if($_GET['mode'] == 0): echo "selected"; endif; endif; ?>>Writers</option>
                                <option value="1" <?php if(isset($_GET['mode'])) : if($_GET['mode'] == 1): echo "selected"; endif; endif; ?>>Editors</option>
                                <option value="2" <?php if(isset($_GET['mode'])) : if($_GET['mode'] == 2): echo "selected"; endif; endif; ?>>Content Admin</option>
                                <option value="3" <?php if(isset($_GET['mode'])) : if($_GET['mode'] == 3): echo "selected"; endif; endif; ?>>Articles With No Categories</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(isset($_GET['mode'])): if($_GET['mode'] ==  0): $writers = $iccaFunc->fetchPerformer(array(5,6)); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Writers</label>
                                    <select class="form-control select2" id="member">
                                        <option value="">ALL</option>
                                        <?php foreach($writers  as $writer): $user = $iccaFunc->getUserById($writer['user_id']); ?>
                                        <option value="<?php echo $user['id']; ?>" <?php if(isset($_GET['member'])) { if($_GET['member'] == $user['id']) { echo "selected"; } } ?>><?php echo $user['fname']." ".$user['lname'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12" style="margin-bottom:10px;">    
                                <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Date From:</h3> 
                                <div class="form-group">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                        <input type="text" style="font-size:14px" class="form-control dpick" name="dateFrom">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12" style="margin-bottom:10px;">    
                                <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Date To:</h3> 
                                <div class="form-group">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                        <input type="text" style="font-size:14px" class="form-control dpick" name="dateTo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body table-responsive">
                                    <table id="writerReportTb" class="table table-bordered table-striped table-hover">
                                        <thead style="background:#2b415e; color:#fff;">
                                            <tr>
                                                <th class="mwidth-180">Writer</th>
                                                <th class="mwidth-250">Approved Rewrites</th>
                                                <th class="mwidth-250">Pending Rewrites</th>
                                                <th class="mwidth-250">Rejected Rewrites</th>
                                                <!--<th class="mwidth-250">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="mwidth-180">Writer</th>
                                                <th class="mwidth-250">Approved Rewrites</th>
                                                <th class="mwidth-250">Pending Rewrites</th>
                                                <th class="mwidth-250">Rejected Rewrites</th>
                                                <!--<th class="mwidth-250">Action</th> -->
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h6 class="box-title">Mturk Writers</h6>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body table-responsive">
                                    <table id="mturkWritersReportTb" class="table table-bordered table-striped table-hover">
                                        <thead style="background:#2b415e; color:#fff;">
                                            <tr>
                                                <th class="mwidth-180">Writer</th>
                                                <th class="mwidth-250">Approved Rewrites</th>
                                                <th class="mwidth-250">Pending Rewrites</th>
                                                <th class="mwidth-250">Rejected Rewrites</th>
                                                <!--<th class="mwidth-250">Action</th> -->
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="mwidth-180">Writer</th>
                                                <th class="mwidth-250">Approved Rewrites</th>
                                                <th class="mwidth-250">Pending Rewrites</th>
                                                <th class="mwidth-250">Rejected Rewrites</th>
                                                <!--<th class="mwidth-250">Action</th> -->
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Modal -->
        <div class="modal fade" id="rejectedDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rejected Rewrites</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <table id="rejectedDetailsTb" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="mwidth-200" bgcolor="#00a65a" style="color: white;">Date</th>
                                        <th class="mwidth-120" bgcolor="#00a65a" style="color: white;">Reviewed By</th>
                                        <th class="mwidth-130" bgcolor="#00a65a" style="color: white;">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-200" bgcolor="#00a65a" style="color: white;">Date</th>
                                        <th class="mwidth-120" bgcolor="#00a65a" style="color: white;">Reviewed By</th>
                                        <th class="mwidth-130" bgcolor="#00a65a" style="color: white;">Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; endif; ?>
    
    
    <?php if(isset($_GET['mode'])): if($_GET['mode'] ==  1): ?>
        <?php 
            $editors = $iccaFunc->fetchPerformer(array(3,6));
        ?>
        <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Editors</label>
                            <select class="form-control select2" id="member">
                                <option value="">ALL</option>
                                <?php foreach($editors as $editor): $user = $iccaFunc->getUserById($editor['user_id']); ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['fname']." ".$user['lname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12" style="margin-bottom:10px;">    
                        <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Date From:</h3> 
                        <div class="form-group">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                <input type="text" style="font-size:14px" class="form-control dpick" name="dateFrom">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12" style="margin-bottom:10px;">    
                        <h3 class="box-title" style="font-weight:bold;margin-top:3px;font-size:14px;">Date To:</h3> 
                        <div class="form-group">
                            <div class="input-group date">
                                <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                <input type="text" style="font-size:14px" class="form-control dpick" name="dateTo">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-body table-responsive">
                                <table id="editorsReportTb" class="table table-bordered table-striped table-hover">
                                    <thead style="background:#2b415e; color:#fff;">
                                        <tr>
                                            <th class="mwidth-180">Editor</th>
                                            <th class="mwidth-250">Approved Rewrites</th>
                                            <th class="mwidth-250">Rejected Rewrites</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="mwidth-180">Editor</th>
                                            <th class="mwidth-250">Approved Rewrites</th>
                                            <th class="mwidth-250">Rejected Rewrites</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
        <!-- Modal -->
        <div class="modal fade" id="rejectedDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rejected Rewrites</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <table id="rejectedDetailsTb" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="mwidth-200" bgcolor="#00a65a" style="color: white;">Date</th>
                                        <th class="mwidth-120" bgcolor="#00a65a" style="color: white;">Writer</th>
                                        <th class="mwidth-130" bgcolor="#00a65a" style="color: white;">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-200" bgcolor="#00a65a" style="color: white;">Date</th>
                                        <th class="mwidth-120" bgcolor="#00a65a" style="color: white;">Writer</th>
                                        <th class="mwidth-130" bgcolor="#00a65a" style="color: white;">Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; endif; ?>
    
    
    <?php if(isset($_GET['mode'])): if($_GET['mode'] ==  2): ?>
        <?php 
            $contentAds = $iccaFunc->fetchPerformer(array(4,1));
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Content Admin</label>
                                <select class="form-control select2" id="member">
                                    <option value="">ALL</option>
                                    <?php foreach($contentAds as $contentAd): $user = $iccaFunc->getUserById($contentAd['user_id']); ?>
                                    <option value="<?php echo $user['id']; ?>"><?php echo $user['fname']." ".$user['lname'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body table-responsive">
                                    <table id="contenAdminTb" class="table table-bordered table-striped table-hover">
                                        <thead style="background:#2b415e; color:#fff;">
                                            <tr>
                                                <th class="mwidth-180">Content Admin</th>
                                                <th class="mwidth-250">Article</th>
                                                <th class="mwidth-250">Date Added</th>
                                                <th class="mwidth-250">Status</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="mwidth-180">Content Admin</th>
                                                <th class="mwidth-250">Article</th>
                                                <th class="mwidth-250">Date Added</th>
                                                <th class="mwidth-250">Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; endif; ?>
    
    <?php if(isset($_GET['mode']) && $_GET['mode'] == 3) { 
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Articles With No Categories</h3>
                        <hr style="margin-top: 5px;">
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Article:</label>
                                <select id="articleTitleFilter" class="form-control select2">
                                    <option value="">All</option>
                                    <?php
                                        $articles = $icca_new_obj->getArticles(' WHERE status = 1 AND category_id = 0 ORDER by title');
                                        
                                        foreach($articles as $article){
                                    ?>
                                    <option value="<?php echo $article['id']; ?>"><?php echo $article['title']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-weight:bold;margin-top:3px;font-size:14px;">Content Writer:</label>
                                    <select class="form-control select2" id="filterByCreator">
                                        <option value="">All</option>
                                        <?php $editors = $iccaFunc->fetchArticleCreator(); ?>
                                        <?php foreach ($editors as $editor) : ?>
                                        <option value='<?php echo $editor['user_id']; ?>'><?php echo $editor['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">    
                                <label style="font-weight:bold;margin-top:3px;font-size:14px;">Date:</label>
                                <div class="form-group">
                                    <div class="input-group date">
                                        <div class="input-group-addon"><i class="fas fa-calendar-alt"></i></div>
                                        <input type="text" style="font-size:14px" class="form-control dpick" name="dateFilter">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2 col-sm-12">    
                                <div class="form-group">
                                    <label style="font-weight:bold;margin-top:3px;font-size:14px;">Export Status:</label>
                                    <select class="form-control select2" id="filterByExportStatus">
                                        <option value="">All</option>
                                        <option value="1">Ready</option>
                                        <option value="0">Not Ready</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <div class="col-lg-12 col-md-12" style="padding:0">
                                <div class="dataTables_filter">
                                    <label>
                                        Search:
                                        <input type="search" class="form-control input-sm" placeholder="" aria-controls="articlesTable" id="articlesTable_filter">
                                    </label>
                                </div>
                            </div>
                            <table id="articlesWithNoCategoriesTb" class="table table-bordered table-striped table-hover">
                                <thead style="background:#2b415e; color:#fff;">
                                    <tr>
                                        <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Id</th>
                                        <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Title</th>
                                        <th class="mwidth-120" bgcolor="#2b415e" style="color: white;">Created By</th>
                                        <th class="mwidth-130" bgcolor="#2b415e" style="color: white;">Date Created</th>
                                        <th class="mwidth-80" bgcolor="#2b415e" style="color: white;">Status</th>
                                        <th class="mwidth-80" bgcolor="#2b415e" style="color: white;">Export Status</th>
                                        <th class="mwidth-200" bgcolor="#2b415e" style="color: white;">Action</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="mwidth-200">Id</th>
                                        <th class="mwidth-200">Title</th>
                                        <th class="mwidth-120">Created By</th>
                                        <th class="mwidth-130">Date Created</th>
                                        <th class="mwidth-80">Status</th>
                                        <th class="mwidth-80">Export Status</th>
                                        <th class="mwidth-200">Action</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        
        <!-- Assign category modal -->
        <div class="modal fade" id="categoryModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form role="form" method="post" action="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="categoryModalTitle">Assign category to article</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="article_id" id="article_id">
                                    <input type="hidden" name="article_title" id="article_title">
                                    <input type="hidden" name="user_id" id="user_id">
                                    <div class="form-group">
                                        <label>Article:</label> 
                                        <p id="article_name"></p>
                                    </div>
                                    <div class="form-group">
                                        <label>Category:</label> 
                                        <select class="form-control select2 select2-hidden-accessible" name="category_id" id="category_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                            <option value="">Fetching...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group mb-0">
                                <button class="btn btn-success" type="submit" id="assignCategory" name="assignCategory">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Assign category modal -->
    <?php } ?>
</section>

<?php if(isset($_GET['mode']) && $_GET['mode'] == 3) { ?>
    <script>
        $(document).ready(function() {
            const articleListTb = $("#articlesWithNoCategoriesTb").DataTable({
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/ajax/articleListDataTable.php",
                    "data": function (d) {
                        d.userLogin = <?php echo $_SESSION['login_id']; ?>
                    }
                },
                "order":[[ 3, "desc" ]],
                
                "columnDefs": [
                    {
                        "targets": [0],
                        "visible": false
                    },
                    {
                        "targets":[7],
                        "visible": false,
                    }],
                "sDom": 'lrtip'
            
            });
                
            $("#articlesTable_filter").on("keyup", function() {
                articleListTb.search(this.value).draw() ;
            });
            
            $('#filterByStatus').on("change", function(){
                articleListTb.column(4).search(this.value).draw();
            })
            
            $('#filterByExportStatus').on("change", function(){
                articleListTb.column(5).search(this.value).draw();
            })
            
            $('#filterByCreator').on("change", function(){
                articleListTb.column(2).search(this.value).draw();
            })
                
            $("#articleTitleFilter").on("change", function(){
                articleListTb.search(this.value).draw();
            })
            
            $("input[name=dateFilter]").on("changeDate", function() {
                console.log(this.value)
                articleListTb.search(this.value.trim()).draw();
            });
            
            //category dropdown
            jQuery.ajax({
                url :"./ajax/export-category-dropdown.php",
                type: "GET", 
                success: function(data) {
                    $('#category_id').html(data);
                },
                error: function(xhr, status, error){  
                    console.log(xhr)
                    console.log(xhr.responseText, status, error);
                    alert("Error occurred when fetching categories for articles. Please try again later.");
                }
            });
        });
        
        function showCategoryModal(id, title, user_id) {
            $('#article_id').val(id);
            $('#user_id').val(user_id);
            $('#article_title').val(title);
            $('#article_name').html(title);
            
            $('#categoryModal').modal('show');
        }
    </script>
<?php } ?>

<?php if(isset($_GET['mode'])): if($_GET['mode'] == 2): ?>
    <script>
    $(document).ready(function(){
        const contentAdminTb = $("#contenAdminTb").DataTable({
            "bDestroy": true,
            "processing" : true,
            "serverSide" : true,
            "ajax" : {
                "url": "/lib/contentAdminReport.php",
                /*
                "data": function (d) {
                    d.dateFrom = $("input[name=dateFrom]").val(),
                    d.dateTo = $("input[name=dateTo]").val()
                   
                } */
            },
            "order":[[ 2, "desc" ]],
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

        $("#member").on("change", function(){
            $("#contenAdminTb").DataTable({
                "bDestroy": true,
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/lib/contentAdminReport.php",
                    "data": function (d) {
                        d.contentAdmin = $("#member").val();
                        //d.dateFrom = $("input[name=dateFrom]").val(),
                        //d.dateTo = $("input[name=dateTo]").val()
                       
                    } 
                },
                "order":[[ 2, "desc" ]],
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
        })
    })
    
        
</script>
<?php endif; endif; ?>

<?php if(isset($_GET['mode'])): if($_GET['mode'] == 1): ?>
    <script>
        $(document).ready(function(){
            var d = new Date();
            var currMonth = d.getMonth();
            var currYear = d.getFullYear();
            var startDate = new Date(currYear, currMonth, 1);
            
            $("input[name=dateFrom]").datepicker({ autoclose: true });
            $("input[name=dateFrom]").datepicker("setDate", startDate);
            
            $("input[name=dateTo]").datepicker({ autoclose: true });
            $("input[name=dateTo]").datepicker("setDate", new Date());
            
            const editorReportTb = $("#editorsReportTb").DataTable({
                "bDestroy": true,
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/lib/editorReportTb.php",
                    "data": function (d) {
                        d.dateFrom = $("input[name=dateFrom]").val(),
                        d.dateTo = $("input[name=dateTo]").val()
                    }
                },
                "order":[[ 0, "desc" ]],
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
        })
        
        $("input[name=dateFrom]").on("changeDate", function(){
                
                $("#editorsReportTb").DataTable({
                    "bDestroy": true,
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        "url": "/lib/editorReportTb.php",
                        "data": function (d) {
                            d.editor = $("#member").val();
                            d.dateFrom = $("input[name=dateFrom]").val(),
                            d.dateTo = $("input[name=dateTo]").val()
                        }
                    },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
                })
            })
            
        $("input[name=dateTo]").on("changeDate", function(){
                
                $("#editorsReportTb").DataTable({
                    "bDestroy": true,
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        "url": "/lib/editorReportTb.php",
                        "data": function (d) {
                            d.editor = $("#member").val();
                            d.dateFrom = $("input[name=dateFrom]").val(),
                            d.dateTo = $("input[name=dateTo]").val()
                        }
                    },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
                })
            });
            
        $("#member").on("change", function(){
                $("#editorsReportTb").DataTable({
                    "bDestroy": true,
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        "url": "/lib/editorReportTb.php",
                        "data": function (d) {
                            d.editor = $("#member").val();
                            d.dateFrom = $("input[name=dateFrom]").val(),
                            d.dateTo = $("input[name=dateTo]").val()
                        }
                    },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
                })
            })
        
        function rejected(e){
            
            var dF = $("input[name=dateFrom]").val().split("/");
            var dF_final = dF[2]+"-"+dF[0]+"-"+dF[1]+" 00:00:00";
            
            var dT = $("input[name=dateTo]").val().split("/");
            var dT_final = dT[2]+"-"+dT[0]+"-"+dT[1]+" 23:59:59";
            
            const rejectedDetailsTb = $("#rejectedDetailsTb").DataTable({
                "bDestroy": true,
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/lib/editorRejectedDetailsTb.php",
                    "data": function (d) {
                        d.editor = e,
                        d.dateFrom = dF_final,
                        d.dateTo = dT_final
                    }
                },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
            })
        }
    </script>
<?php endif; endif; ?>

<?php if(isset($_GET['mode'])): if($_GET['mode'] == 0): ?>
    <script>
        $(document).ready(function() {
            var d = new Date();
            var currMonth = d.getMonth();
            var currYear = d.getFullYear();
            var startDate = new Date(currYear, currMonth, 1);
            
            $("input[name=dateFrom]").datepicker({ autoclose: true });
            $("input[name=dateFrom]").datepicker("setDate", startDate);
            
            $("input[name=dateTo]").datepicker({ autoclose: true });
            $("input[name=dateTo]").datepicker("setDate", new Date());
            
            const writerReportTb = $("#writerReportTb").DataTable({
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/lib/writerReportTb.php",
                    "data": function (d) {
                        d.dateFrom = $("input[name=dateFrom]").val(),
                        d.dateTo = $("input[name=dateTo]").val()
                    }
                },
                "order":[[ 0, "desc" ]],
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
            
            $("input[name=dateFrom]").on("changeDate", function(){
                
                $("#writerReportTb").DataTable({
                    "bDestroy": true,
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        "url": "/lib/writerReportTb.php",
                        "data": function (d) {
                            d.writer = $("#member").val();
                            d.dateFrom = $("input[name=dateFrom]").val(),
                            d.dateTo = $("input[name=dateTo]").val()
                        }
                    },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
                })
            })
            
            $("input[name=dateTo]").on("changeDate", function(){
                $("#writerReportTb").DataTable({
                    "bDestroy": true,
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        "url": "/lib/writerReportTb.php",
                        "data": function (d) {
                            d.writer = $("#member").val();
                            d.dateFrom = $("input[name=dateFrom]").val(),
                            d.dateTo = $("input[name=dateTo]").val()
                        }
                    },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
                })
            });
            
            $("#member").on("change", function(){
                $("#writerReportTb").DataTable({
                    "bDestroy": true,
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        "url": "/lib/writerReportTb.php",
                        "data": function (d) {
                            d.writer = $("#member").val();
                            d.dateFrom = $("input[name=dateFrom]").val(),
                            d.dateTo = $("input[name=dateTo]").val()
                        }
                    },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
                })
            });
            
            const mturkWritersReportTb = $("#mturkWritersReportTb").DataTable({
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/lib/mturkWriterReportTb.php",
                    "data": function (d) {
                        d.dateFrom = $("input[name=dateFrom]").val(),
                        d.dateTo = $("input[name=dateTo]").val()
                    }
                },
                "order":[[ 0, "desc" ]],
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
        });
        
        function rejected(e){
            
            var dF = $("input[name=dateFrom]").val().split("/");
            var dF_final = dF[2]+"-"+dF[0]+"-"+dF[1]+" 00:00:00";
            
            var dT = $("input[name=dateTo]").val().split("/");
            var dT_final = dT[2]+"-"+dT[0]+"-"+dT[1]+" 23:59:59";
            
            const rejectedDetailsTb = $("#rejectedDetailsTb").DataTable({
                "bDestroy": true,
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    "url": "/lib/writerRejectedDetailsTb.php",
                    "data": function (d) {
                        d.writer = e,
                        d.dateFrom = dF_final,
                        d.dateTo = dT_final
                    }
                },
                "order":[[ 0, "desc" ]],
                "sDom": 'lrtip',
            })
        }
    </script>
<?php endif; endif; ?>

<script>
    $(document).ready(function(){
        $("#viewMode").on("change", function(){
            location.href="?page=performanceReport&mode="+$("#viewMode").val();
        });
        $('.dpick').datepicker({ 
            format: "yyyy-mm-dd",
            endDate: new Date(),
            autoclose: true,
        });
        $("#table1").DataTable({"order":[[ 1, "desc" ]]});    
    });
</script>